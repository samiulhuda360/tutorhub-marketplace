<?php

namespace App\Matching;

use App\Ai\AiException;
use App\Ai\Embeddings;
use App\Ai\LlmClient;
use App\Models\TuitionOffer;
use App\Models\TutorProfile;
use App\Models\User;
use App\Support\TuitionCatalog as C;
use Illuminate\Support\Collection;

/**
 * Ranks tutors for an offer, and offers for a tutor.
 *
 * Hard rules first (active profile, at least one subject in common, the gender the
 * parent asked for). Then points out of 100:
 *   subjects 35, class 20, area 15, budget 10, profile fit 8, experience 8, verified 4.
 * "Profile fit" is the similarity of the offer's description to the tutor's bio
 * (embeddings when AI is on, word overlap otherwise), scaled within the candidates.
 *
 * The score and reasons are computed in code, so they are the same every time and
 * can be checked. The model only turns them into a sentence for the parent.
 */
class TutorMatcher
{
    private const WEIGHTS = ['subjects' => 35, 'class' => 20, 'area' => 15, 'budget' => 10, 'fit' => 8, 'experience' => 8, 'verified' => 4];

    public function __construct(private readonly Embeddings $embeddings, private readonly LlmClient $llm)
    {
    }

    /** @return Collection<int, MatchResult> */
    public function forOffer(TuitionOffer $offer, int $limit = 5, bool $explain = false): Collection
    {
        $offer->loadMissing('area');
        $candidates = TutorProfile::with('user')
            ->where('is_active', true)
            ->whereHas('user', fn ($q) => $q->where('role', 'tutor'))
            ->get()
            ->filter(fn (TutorProfile $p) => $this->passesHardRules($offer, $p));

        $results = $this->rank($offer, $candidates)->take($limit)->values();
        if ($explain) {
            $this->explain($results);
        }

        return $results;
    }

    /** Open offers that fit a tutor best (the tutor's "offers for you"). @return Collection<int, MatchResult> */
    public function forTutor(User $tutor, int $limit = 5): Collection
    {
        $profile = $tutor->tutorProfile;
        if (! $profile || ! $profile->is_active) {
            return collect();
        }

        return TuitionOffer::with('area')->where('status', 'open')->latest('posted_at')->limit(200)->get()
            ->filter(fn (TuitionOffer $o) => $this->passesHardRules($o, $profile))
            ->map(fn (TuitionOffer $o) => $this->score($o, $profile, 0.5))
            ->sortByDesc('score')
            ->take($limit)
            ->values();
    }

    public function passesHardRules(TuitionOffer $offer, TutorProfile $p): bool
    {
        if (! array_intersect($this->lower($offer->subjects), $this->lower($p->subjects))) {
            return false;
        }
        $wanted = $offer->tutor_gender_preference;

        return ! in_array($wanted, ['male', 'female'], true) || $p->gender === $wanted;
    }

    /** Score every candidate, with "profile fit" scaled across the candidates. @return Collection<int, MatchResult> */
    public function rank(TuitionOffer $offer, Collection $profiles): Collection
    {
        if ($profiles->isEmpty()) {
            return collect();
        }

        $items = ['offer' => ['type' => 'offer', 'id' => $offer->id, 'text' => $this->offerText($offer)]];
        foreach ($profiles as $p) {
            $items['tutor:'.$p->id] = ['type' => 'tutor_profile', 'id' => $p->id, 'text' => $this->profileText($p)];
        }
        $vectors = $this->embeddings->forOwners($items, 'matching')['vectors'];
        $sims = $profiles->mapWithKeys(fn ($p) => [$p->id => Embeddings::cosine($vectors['offer'], $vectors['tutor:'.$p->id])]);
        [$lo, $hi] = [$sims->min(), $sims->max()];

        return $profiles
            ->map(fn (TutorProfile $p) => $this->score($offer, $p, $hi > $lo ? ($sims[$p->id] - $lo) / ($hi - $lo) : 1.0))
            ->sort(fn (MatchResult $a, MatchResult $b) => [$b->score, $a->tutor->id] <=> [$a->score, $b->tutor->id])
            ->values();
    }

    /** @param float $fit 0..1, how close the tutor's bio is to the offer compared with the other candidates */
    public function score(TuitionOffer $offer, TutorProfile $p, float $fit): MatchResult
    {
        $parts = $reasons = $concerns = [];
        $add = function (string $key, string $label, float $share) use (&$parts) {
            $parts[] = ['key' => $key, 'label' => $label, 'points' => round(self::WEIGHTS[$key] * max(0, min(1, $share)), 1), 'max' => self::WEIGHTS[$key]];
        };

        $wanted = array_values((array) $offer->subjects);
        $common = array_values(array_filter($wanted, fn ($s) => in_array(mb_strtolower($s), $this->lower($p->subjects), true)));
        $add('subjects', 'Subjects', $wanted ? count($common) / count($wanted) : 0);
        $reasons[] = count($common) === count($wanted)
            ? 'Teaches '.$this->list($common)
            : 'Teaches '.$this->list($common).' ('.count($common).' of '.count($wanted).' subjects)';
        if (count($common) < count($wanted)) {
            $concerns[] = 'Does not list '.$this->list(array_diff($wanted, $common));
        }

        $classFit = C::classFit((string) $offer->student_class, (array) $p->classes);
        $add('class', 'Class', $classFit);
        if ($classFit >= 1) {
            $reasons[] = 'Teaches '.$offer->student_class.' students';
        } elseif ($classFit > 0) {
            $reasons[] = 'Teaches a class next to '.$offer->student_class;
        } else {
            $concerns[] = 'Has not taught '.$offer->student_class;
        }

        $areaName = $offer->area?->name;
        if ($offer->tuition_type === 'online') {
            $add('area', 'Area', 1);
            $reasons[] = 'Online classes, so location does not matter';
        } elseif (in_array((int) $offer->area_id, array_map('intval', (array) $p->areas), true)) {
            $add('area', 'Area', 1);
            $reasons[] = 'Works in '.$areaName;
        } else {
            $add('area', 'Area', 0);
            $concerns[] = 'Does not list '.$areaName.' as an area';
        }

        $rate = (float) $p->hourly_rate;
        $budget = (float) $offer->salary;
        if ($budget <= 0 || $rate <= 0) {
            $add('budget', 'Budget', 0.5);
        } elseif ($rate <= $budget) {
            $add('budget', 'Budget', 1);
            $reasons[] = 'Asks '.$this->taka($rate).' a month, within the '.$this->taka($budget).' budget';
        } elseif ($rate <= $budget * 1.2) {
            $add('budget', 'Budget', 0.5);
            $concerns[] = 'Asks '.$this->taka($rate).', a little over the '.$this->taka($budget).' budget';
        } else {
            $add('budget', 'Budget', 0);
            $concerns[] = 'Asks '.$this->taka($rate).', well over the '.$this->taka($budget).' budget';
        }

        $add('fit', 'Profile fit', $fit);

        $years = (int) $p->experience_years;
        $add('experience', 'Experience', min($years, 8) / 8);
        if ($years >= 3) {
            $reasons[] = $years.' years of teaching experience';
        }

        $add('verified', 'Verified', $p->is_verified ? 1 : 0);
        if ($p->is_verified) {
            $reasons[] = 'Profile verified by TutorHub';
        }

        $score = (int) round(array_sum(array_column($parts, 'points')));

        return new MatchResult($p->user, $offer, $score, $parts, $reasons, $concerns);
    }

    /**
     * One sentence per tutor for the parent, written by the model from the facts above
     * (one call for the whole list). Falls back to joining the top reasons.
     *
     * @param  Collection<int, MatchResult>  $results
     */
    public function explain(Collection $results): void
    {
        foreach ($results as $r) {
            $r->explanation = $this->ruleSentence($r);
            $r->explanationSource = 'rules';
        }
        if ($results->isEmpty() || ! $this->llm->isLive()) {
            return;
        }

        $facts = $results->map(fn (MatchResult $r) => [
            'tutor_code' => $r->tutor->tutor_code,
            'name' => strtok($r->tutor->name, ' '),
            'score' => $r->score,
            'fits' => $r->reasons,
            'does_not_fit' => $r->concerns,
            'bio' => mb_substr((string) $r->tutor->tutorProfile?->bio, 0, 300),
        ])->values()->all();

        try {
            $out = $this->llm->json('match_explanations',
                "You explain to a parent in Dhaka why each tutor was suggested for their tuition offer.\n"
                ."Write one plain sentence per tutor, at most 30 words, using only the facts given. Start with the tutor's "
                ."name and refer to them by name, not he or she. Say what fits best and, only if there is one, the main "
                ."thing that does not fit. Never mention the score or the tutor code in the sentence. Copy each tutor_code "
                ."exactly into the answer. No praise words, no invented details.",
                'Offer: '.$results->first()->offer->title."\nTutors:\n".json_encode($facts, JSON_UNESCAPED_UNICODE),
                ['type' => 'object', 'properties' => ['explanations' => ['type' => 'array', 'items' => [
                    'type' => 'object',
                    'properties' => ['tutor_code' => ['type' => 'string'], 'sentence' => ['type' => 'string']],
                    'required' => ['tutor_code', 'sentence'],
                ]]], 'required' => ['explanations']],
                600,
            );
        } catch (AiException) {
            return;
        }

        // Match answers to tutors by code, then by first name, then by position (models do not always copy ids).
        $answers = collect($out['explanations'] ?? [])->values();
        $byCode = $answers->keyBy(fn ($a) => mb_strtolower(trim((string) ($a['tutor_code'] ?? ''))));
        foreach ($results->values() as $i => $r) {
            $first = mb_strtolower(strtok($r->tutor->name, ' '));
            $answer = $byCode[mb_strtolower((string) $r->tutor->tutor_code)] ?? $byCode[$first]
                ?? ($answers->count() === $results->count() ? $answers[$i] : null);
            $sentence = trim((string) ($answer['sentence'] ?? ''));
            if ($sentence !== '' && mb_strlen($sentence) <= 260) {
                $r->explanation = $sentence;
                $r->explanationSource = 'ai';
            }
        }
    }

    private function ruleSentence(MatchResult $r): string
    {
        $text = implode('; ', array_slice($r->reasons, 0, 3));

        return $r->concerns ? $text.'. But: '.lcfirst($r->concerns[0]).'.' : $text.'.';
    }

    private function offerText(TuitionOffer $o): string
    {
        return trim($o->title.'. '.$o->description.' Subjects: '.implode(', ', (array) $o->subjects).'. '.$o->student_class.'.');
    }

    private function profileText(TutorProfile $p): string
    {
        return trim($p->bio.' '.$p->education.'. Teaches '.implode(', ', (array) $p->subjects).' for '.implode(', ', (array) $p->classes).'.');
    }

    private function lower(mixed $values): array
    {
        return array_map(fn ($v) => mb_strtolower((string) $v), (array) $values);
    }

    private function list(array $items): string
    {
        $items = array_values($items);

        return count($items) <= 1 ? (string) ($items[0] ?? '') : implode(', ', array_slice($items, 0, -1)).' and '.end($items);
    }

    private function taka(float $amount): string
    {
        return '৳'.number_format($amount);
    }
}

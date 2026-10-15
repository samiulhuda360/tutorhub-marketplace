<?php

namespace App\Ai;

use App\Models\Area;
use App\Models\TuitionOffer;

/**
 * First drafts of a tutor's bio and of a cover letter for an offer. The tutor edits
 * and sends them; nothing is published by itself.
 *
 * Grounding guard: a draft that contains a number the tutor's own details do not
 * (years, grades, prices) is thrown away and the template draft is used instead,
 * because an invented "10 years of experience" is worse than a plain letter.
 */
class ProfileWriter
{
    public function __construct(private readonly LlmClient $llm)
    {
    }

    /** @param array{name?: string, education?: string, experience_years?: int|string, subjects?: array, classes?: array, areas?: array, availability?: string} $p */
    public function bio(array $p): array
    {
        $facts = $this->facts($p);
        $fallback = $this->templateBio($p);
        if (! $this->llm->isLive()) {
            return ['text' => $fallback, 'source' => 'rules'];
        }

        try {
            $text = $this->llm->text('profile_bio',
                "You write a short first-person bio for a private tutor's profile in Dhaka, for parents to read.\n"
                ."Use only the facts given. Do not invent results, grades, awards, numbers or teaching methods. "
                ."Plain, warm English, 60 to 110 words, no headings, no emoji, no hashtags.",
                "Facts:\n".$facts,
                300,
            );
        } catch (AiException) {
            return ['text' => $fallback, 'source' => 'rules'];
        }

        return self::grounded($text, $facts) && mb_strlen($text) <= 1000
            ? ['text' => $text, 'source' => 'ai']
            : ['text' => $fallback, 'source' => 'rules'];
    }

    public function coverLetter(TuitionOffer $offer, array $p): array
    {
        $facts = $this->facts($p)."\nOffer: ".$offer->title.'. '.$offer->description
            ."\nOffer details: ".$offer->student_class.'; '.implode(', ', (array) $offer->subjects).'; '
            .$offer->days_per_week.' days a week; '.$offer->tuition_type.'; '.number_format((float) $offer->salary).' taka a month';
        $fallback = $this->templateLetter($offer, $p);
        if (! $this->llm->isLive()) {
            return ['text' => $fallback, 'source' => 'rules'];
        }

        try {
            $text = $this->llm->text('cover_letter',
                "You draft a short application message from a tutor to a parent who posted a tuition offer in Dhaka.\n"
                ."Use only the facts given about the tutor and the offer. Address the offer's subjects, class and schedule. "
                ."Do not invent results, grades or experience. 70 to 140 words, polite and direct, no subject line, "
                ."no placeholders like [Name].",
                $facts,
                350,
            );
        } catch (AiException) {
            return ['text' => $fallback, 'source' => 'rules'];
        }

        $text = trim($text);
        $ok = self::grounded($text, $facts) && mb_strlen($text) >= 50 && mb_strlen($text) <= 1000 && ! str_contains($text, '[');

        return $ok ? ['text' => $text, 'source' => 'ai'] : ['text' => $fallback, 'source' => 'rules'];
    }

    /** Every number in the draft must appear in the facts it was written from. */
    public static function grounded(string $draft, string $facts): bool
    {
        preg_match_all('/\d+(?:[.,]\d+)?/', $draft, $m);
        preg_match_all('/\d+(?:[.,]\d+)?/', $facts, $f);
        $known = array_map(fn ($n) => str_replace(',', '', $n), $f[0]);
        foreach ($m[0] as $n) {
            if (! in_array(str_replace(',', '', $n), $known, true)) {
                return false;
            }
        }

        return true;
    }

    private function facts(array $p): string
    {
        $areas = Area::whereIn('id', (array) ($p['areas'] ?? []))->pluck('name')->all();

        return implode("\n", array_filter([
            isset($p['name']) ? 'Name: '.$p['name'] : null,
            ! empty($p['education']) ? 'Education: '.$p['education'] : null,
            isset($p['experience_years']) ? 'Years teaching: '.(int) $p['experience_years'] : null,
            ! empty($p['subjects']) ? 'Subjects: '.implode(', ', (array) $p['subjects']) : null,
            ! empty($p['classes']) ? 'Classes: '.implode(', ', (array) $p['classes']) : null,
            $areas ? 'Areas: '.implode(', ', $areas) : null,
            ! empty($p['availability']) ? 'Availability: '.$p['availability'] : null,
        ]));
    }

    private function templateBio(array $p): string
    {
        $years = (int) ($p['experience_years'] ?? 0);
        $parts = [];
        $parts[] = 'I teach '.implode(', ', (array) ($p['subjects'] ?? ['several subjects']))
            .' for '.implode(', ', (array) ($p['classes'] ?? ['school students'])).'.';
        if (! empty($p['education'])) {
            $parts[] = 'I studied '.$p['education'].'.';
        }
        if ($years > 0) {
            $parts[] = 'I have '.$years.' '.($years === 1 ? 'year' : 'years').' of experience as a private tutor.';
        }
        $parts[] = 'I explain each topic step by step, give regular practice and keep parents updated on progress.';
        if (! empty($p['availability'])) {
            $parts[] = 'Available: '.$p['availability'].'.';
        }

        return implode(' ', $parts);
    }

    private function templateLetter(TuitionOffer $offer, array $p): string
    {
        $years = (int) ($p['experience_years'] ?? 0);

        return 'Assalamu Alaikum. I would like to teach '.implode(' and ', (array) $offer->subjects)
            .' for your '.$offer->student_class.' student.'
            .($years > 0 ? ' I have '.$years.' '.($years === 1 ? 'year' : 'years').' of tutoring experience' : ' I am a dedicated tutor')
            .(! empty($p['education']) ? ' and studied '.$p['education'].'.' : '.')
            .' I can come '.$offer->days_per_week.' days a week and will share a short plan after the first class.'
            .' I would be glad to discuss timing and the student\'s goals with you.';
    }
}

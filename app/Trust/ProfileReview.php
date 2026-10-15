<?php

namespace App\Trust;

use App\Ai\Embeddings;
use App\Models\Application;
use App\Models\TutorProfile;
use Illuminate\Support\Collection;

/**
 * Tutor profiles an admin should look at before verifying them, with the reason.
 *
 * Signals (each one explainable on its own; nothing here blocks anyone automatically):
 * - the same phone number on more than one profile
 * - a bio that is nearly a copy of another tutor's bio
 * - the same cover letter sent by different tutors
 * - more years of experience than the tutor's age allows
 */
class ProfileReview
{
    public const BIO_THRESHOLD = 0.9;

    public const LETTER_THRESHOLD = 0.95;

    /** @return Collection<int, array{profile: TutorProfile, signals: list<string>}> keyed by profile id */
    public function flagged(): Collection
    {
        $profiles = TutorProfile::with('user')->get()->keyBy('id');
        $signals = [];
        $flag = function (int $id, string $why) use (&$signals) {
            $signals[$id][] = $why;
        };

        // Shared phone numbers (compare the last 10 digits, so +880 and 0 prefixes match).
        $profiles->groupBy(fn ($p) => substr(preg_replace('/\D/', '', (string) $p->phone), -10))
            ->filter(fn ($group, $phone) => strlen($phone) >= 10 && $group->count() > 1)
            ->each(function ($group) use ($flag) {
                foreach ($group as $p) {
                    $others = $group->where('id', '!=', $p->id)->map(fn ($o) => $o->user->name)->implode(', ');
                    $flag($p->id, 'Same phone number as '.$others);
                }
            });

        // Near-copied bios. Lexical vectors: a copy is a copy in any language, and this needs no API calls.
        $bios = $profiles->filter(fn ($p) => mb_strlen((string) $p->bio) >= 60)
            ->map(fn ($p) => Embeddings::lexical((string) $p->bio));
        $ids = $bios->keys()->all();
        foreach ($ids as $i => $a) {
            foreach (array_slice($ids, $i + 1) as $b) {
                if (Embeddings::cosine($bios[$a], $bios[$b]) >= self::BIO_THRESHOLD) {
                    // The newer profile is the likely copy.
                    [$old, $new] = $a < $b ? [$a, $b] : [$b, $a];
                    $flag($new, 'Bio copies '.$profiles[$old]->user->name.'\'s (an older profile)');
                    $flag($old, 'Bio was copied by '.$profiles[$new]->user->name);
                }
            }
        }

        // The same cover letter from different tutors (one tutor reusing their own letter is fine).
        $letters = Application::whereNotNull('cover_letter')->latest('id')->limit(500)->get(['tutor_id', 'cover_letter'])
            ->filter(fn ($a) => mb_strlen($a->cover_letter) >= 80)
            ->map(fn ($a) => ['tutor' => $a->tutor_id, 'vec' => Embeddings::lexical($a->cover_letter)])->values();
        $byUser = $profiles->keyBy('user_id');
        for ($i = 0; $i < $letters->count(); $i++) {
            for ($j = $i + 1; $j < $letters->count(); $j++) {
                [$x, $y] = [$letters[$i], $letters[$j]];
                if ($x['tutor'] !== $y['tutor'] && Embeddings::cosine($x['vec'], $y['vec']) >= self::LETTER_THRESHOLD
                    && isset($byUser[$x['tutor']], $byUser[$y['tutor']])) {
                    $flag($byUser[$x['tutor']]->id, 'Sent the same cover letter as '.$byUser[$y['tutor']]->user->name);
                    $flag($byUser[$y['tutor']]->id, 'Sent the same cover letter as '.$byUser[$x['tutor']]->user->name);
                }
            }
        }

        // Experience that does not fit the date of birth (teaching before 16 is not plausible).
        foreach ($profiles as $p) {
            if ($p->date_of_birth && $p->experience_years > max(0, $p->date_of_birth->age - 16)) {
                $flag($p->id, $p->experience_years.' years of experience at age '.$p->date_of_birth->age);
            }
        }

        return collect($signals)
            ->map(fn ($list, $id) => ['profile' => $profiles[$id], 'signals' => array_values(array_unique($list))])
            ->sortByDesc(fn ($row) => count($row['signals']));
    }
}

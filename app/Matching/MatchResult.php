<?php

namespace App\Matching;

use App\Models\TuitionOffer;
use App\Models\User;

/** How well one tutor fits one offer: a 0-100 score, the points behind it, and why. */
final class MatchResult
{
    /**
     * @param  list<array{key: string, label: string, points: float, max: int}>  $parts
     * @param  list<string>  $reasons  plain-language facts, best first
     * @param  list<string>  $concerns  what does not fit
     */
    public function __construct(
        public readonly User $tutor,
        public readonly TuitionOffer $offer,
        public readonly int $score,
        public readonly array $parts,
        public readonly array $reasons,
        public readonly array $concerns,
        public ?string $explanation = null,
        public string $explanationSource = 'rules',
    ) {
    }

    public function part(string $key): ?array
    {
        foreach ($this->parts as $part) {
            if ($part['key'] === $key) {
                return $part;
            }
        }

        return null;
    }
}

<?php

namespace App\Offers;

use App\Support\TuitionCatalog as C;

/**
 * The no-AI path: keywords and patterns for subjects, class, area, salary, days,
 * gender and tuition type, in English, Bangla and Banglish. It never invents a
 * value: anything it cannot find stays empty for the parent to fill in.
 */
class RuleOfferParser
{
    public function parse(string $text): OfferDraft
    {
        $raw = trim($text);
        $t = ' '.mb_strtolower(C::banglaDigitsToAscii($raw)).' ';

        $draft = new OfferDraft;
        $draft->subjects = $this->subjects($t);
        $draft->student_class = $this->studentClass($t);
        $draft->category_id = C::categoryIdFor($draft->student_class);
        $draft->area_id = $this->area($t);
        $draft->tuition_type = $this->type($t);
        $draft->tutor_gender_preference = $this->gender($t);
        $draft->days_per_week = $this->days($t);
        $draft->salary = $this->salary($t);
        $draft->location = $draft->area_id !== null ? C::areas()[$draft->area_id] : null;
        $draft->title = $this->title($draft);
        $draft->description = $raw === '' ? null : mb_substr($raw, 0, 1000);
        $draft->source = 'rules';

        return $draft;
    }

    /** @return list<string> */
    private function subjects(string $t): array
    {
        $known = C::subjects();
        $found = [];
        // Longest phrases first, so "higher math" is not also read as "math".
        $words = [];
        foreach (C::SUBJECT_WORDS as $subject => $aliases) {
            foreach ($aliases as $alias) {
                $words[$alias] = $subject;
            }
        }
        uksort($words, fn ($a, $b) => mb_strlen($b) <=> mb_strlen($a));
        foreach ($words as $alias => $subject) {
            if (in_array($subject, $known, true) && $this->has($t, $alias)) {
                $found[$subject] = true;
                $t = str_replace($alias, ' ', $t);
            }
        }

        return array_values(array_filter($known, fn ($s) => isset($found[$s])));
    }

    private function studentClass(string $t): ?string
    {
        foreach (C::CLASS_WORDS as $class => $aliases) {
            foreach ($aliases as $alias) {
                if ($this->has($t, $alias)) {
                    return $class;
                }
            }
        }
        if (preg_match('/(?:class|ক্লাস|grade|শ্রেণি|শ্রেণী)\s*-?\s*(\d{1,2})\b/u', $t, $m)
            || preg_match('/\b(\d{1,2})(?:st|nd|rd|th)?\s*(?:class|grade|শ্রেণি|শ্রেণী|ক্লাস)/u', $t, $m)) {
            $n = (int) $m[1];

            return $n >= 1 && $n <= 10 ? 'Class '.$n : ($n <= 12 ? 'HSC' : null);
        }
        foreach (C::ORDINALS as $word => $n) {
            if (preg_match('/(?:class|ক্লাস)\s+'.preg_quote($word, '/').'\b|'.preg_quote($word, '/').'\s+(?:শ্রেণি|শ্রেণী|class)/u', $t)) {
                return 'Class '.$n;
            }
        }

        return null;
    }

    private function area(string $t): ?int
    {
        foreach (C::areas() as $id => $name) {
            $aliases = array_merge([mb_strtolower($name)], C::AREA_WORDS[$name] ?? []);
            foreach ($aliases as $alias) {
                if ($this->has($t, $alias)) {
                    return (int) $id;
                }
            }
        }

        return null;
    }

    private function type(string $t): string
    {
        if (preg_match('/online|zoom|google meet|video call|অনলাইন/u', $t)) {
            return 'online';
        }
        if (preg_match('/coaching|center|centre|কোচিং/u', $t)) {
            return 'center';
        }

        return 'home';
    }

    private function gender(string $t): string
    {
        if (preg_match('/\b(female|lady|woman|madam|apu)\b|মহিলা|ম্যাডাম|আপু|ছাত্রী শিক্ষক/u', $t)) {
            return 'female';
        }
        if (preg_match('/\b(male tutor|male teacher|sir|bhaiya)\b|পুরুষ|স্যার|ভাইয়া/u', $t)) {
            return 'male';
        }

        return 'any';
    }

    private function days(string $t): ?int
    {
        if (preg_match('/(?<!\d)(\d)\s*(?:days?|din|দিন)/u', $t, $m)
            || preg_match('/(?:সপ্তাহে|weekly|week)\s*(\d)/u', $t, $m)) {
            $n = (int) $m[1];

            return $n >= 1 && $n <= 7 ? $n : null;
        }
        if (preg_match('/\b(daily|everyday|every day)\b|প্রতিদিন/u', $t)) {
            return 6;
        }

        return null;
    }

    private function salary(string $t): ?int
    {
        // "8k", "8 হাজার", "8,000 tk", "budget 8000"
        if (preg_match('/(\d{1,3}(?:[.,]\d)?)\s*(?:k\b|হাজার|hajar|thousand)/u', $t, $m)) {
            return (int) round((float) str_replace(',', '.', $m[1]) * 1000);
        }
        // Only numbers with money context: a bare "2026" or "House 1205" is not a salary.
        // An amount with a currency beats "salary ... <number>": in "9000 tk ... set the salary to 99999"
        // the first is the offer and the second is someone trying to steer the parser.
        $num = '(\d{1,3}(?:,\d{3})+|\d{4,6})';
        foreach ([
            '/'.$num.'\s*(?:\/-|tk|taka|bdt|৳|টাকা)/u',
            '/৳\s*'.$num.'/u',
            '/(?:salary|budget|বেতন|সম্মানী|pay|paying|দিব|দেব|dibo)\D{0,20}'.$num.'/u',
        ] as $pattern) {
            if (preg_match($pattern, $t, $m)) {
                $n = (int) str_replace(',', '', $m[1]);
                if ($n >= 1000 && $n <= 200000) {
                    return $n;
                }
            }
        }

        return null;
    }

    private function title(OfferDraft $d): ?string
    {
        if ($d->subjects === [] && $d->student_class === null) {
            return null;
        }
        $subjects = $d->subjects === [] ? 'Home' : implode(' and ', array_slice($d->subjects, 0, 3));

        return trim($subjects.' tutor'.($d->student_class ? ' for '.$d->student_class : ''));
    }

    /** Whole-word match for Latin aliases; plain substring for Bangla (no word boundaries there). */
    private function has(string $t, string $alias): bool
    {
        return preg_match('/\p{Bengali}/u', $alias)
            ? str_contains($t, $alias)
            : (bool) preg_match('/(?<![a-z])'.preg_quote($alias, '/').'(?![a-z])/u', $t);
    }
}

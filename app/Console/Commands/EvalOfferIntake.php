<?php

namespace App\Console\Commands;

use App\Ai\LlmClient;
use App\Offers\OfferDraft;
use App\Offers\OfferDrafter;
use App\Offers\RuleOfferParser;
use App\Support\TuitionCatalog;
use Illuminate\Console\Command;

/**
 * Field-by-field accuracy of the offer intake on hand-labelled parent requests
 * (database/eval/offer-intake.json): rules alone, the model alone, and the model
 * with gaps filled by rules (what the app uses).
 */
class EvalOfferIntake extends Command
{
    protected $signature = 'ai:eval-intake {--out= : write a Markdown report to this path}';

    protected $description = 'Measure the offer intake against labelled requests (rules vs AI)';

    private const FIELDS = ['student_class', 'subjects', 'area', 'tuition_type', 'tutor_gender_preference', 'days_per_week', 'salary'];

    public function handle(RuleOfferParser $rules, OfferDrafter $drafter, LlmClient $llm): int
    {
        $cases = json_decode(file_get_contents(database_path('eval/offer-intake.json')), true);
        $areas = TuitionCatalog::areas();

        $systems = ['rules' => fn (string $t) => $rules->parse($t)];
        if ($llm->isLive()) {
            $systems['ai'] = fn (string $t) => $drafter->draft($t, backfill: false);
            $systems['ai + rules'] = fn (string $t) => $drafter->draft($t, backfill: true);
        } else {
            $this->warn('AI is off (no AI_API_KEY): only the rules are measured.');
        }

        $results = [];
        foreach ($systems as $name => $run) {
            $fieldHits = array_fill_keys(self::FIELDS, 0);
            $perfect = 0;
            $ms = [];
            $misses = [];
            foreach ($cases as $i => $case) {
                $started = hrtime(true);
                $draft = $run($case['text']);
                $ms[] = (hrtime(true) - $started) / 1e6;
                $got = $this->fields($draft, $areas);
                $allRight = true;
                foreach (self::FIELDS as $f) {
                    if ($this->same($got[$f], $case['expect'][$f])) {
                        $fieldHits[$f]++;
                    } else {
                        $allRight = false;
                        $misses[] = sprintf('#%d %s: expected %s, got %s', $i + 1, $f, json_encode($case['expect'][$f], JSON_UNESCAPED_UNICODE), json_encode($got[$f], JSON_UNESCAPED_UNICODE));
                    }
                }
                $perfect += $allRight ? 1 : 0;
            }
            sort($ms);
            $results[$name] = ['fields' => $fieldHits, 'perfect' => $perfect, 'p50' => $ms[(int) floor(count($ms) / 2)], 'misses' => $misses];
        }

        $n = count($cases);
        $header = array_merge(['field'], array_keys($results));
        $rows = [];
        foreach (self::FIELDS as $f) {
            $rows[] = array_merge([$f], array_map(fn ($r) => $this->pct($r['fields'][$f], $n), array_values($results)));
        }
        $rows[] = array_merge(['all 7 fields right'], array_map(fn ($r) => $this->pct($r['perfect'], $n), array_values($results)));
        $rows[] = array_merge(['median time'], array_map(fn ($r) => number_format($r['p50']).' ms', array_values($results)));
        $this->table($header, $rows);

        if ($out = $this->option('out')) {
            $md = "# Offer intake eval\n\n"
                .$n.' hand-labelled parent requests (English, Bangla and Banglish, one with a prompt-injection attempt), '
                .'from `database/eval/offer-intake.json`. Run on '.now()->toDateString()
                .($llm->isLive() ? ' with `'.$llm->chatModel().'`' : ' (rules only)').". Command: `php artisan ai:eval-intake`.\n\n"
                .'| '.implode(' | ', $header)." |\n|".str_repeat('---|', count($header))."\n";
            foreach ($rows as $row) {
                $md .= '| '.implode(' | ', $row)." |\n";
            }
            $md .= "\n\"ai + rules\" is what the app uses: the model's answer, with any field it left empty filled by the rules.\n"
                ."Median time for the AI columns includes the network round trip; repeated requests are served from the cache.\n";
            foreach ($results as $name => $r) {
                $md .= "\n## Misses: {$name} (".count($r['misses']).")\n\n".($r['misses'] ? '- '.implode("\n- ", $r['misses']) : 'None.')."\n";
            }
            file_put_contents(base_path($out), $md);
            $this->info('Report written to '.$out);
        }

        return self::SUCCESS;
    }

    private function fields(OfferDraft $d, array $areas): array
    {
        return [
            'student_class' => $d->student_class, 'subjects' => $d->subjects,
            'area' => $d->area_id !== null ? ($areas[$d->area_id] ?? null) : null,
            'tuition_type' => $d->tuition_type, 'tutor_gender_preference' => $d->tutor_gender_preference,
            'days_per_week' => $d->days_per_week, 'salary' => $d->salary,
        ];
    }

    private function same(mixed $got, mixed $expected): bool
    {
        if (is_array($expected)) {
            $a = array_map('strval', (array) $got);
            sort($a);
            $b = array_map('strval', $expected);
            sort($b);

            return $a === $b;
        }

        return $got === $expected;
    }

    private function pct(int $hits, int $n): string
    {
        return round(100 * $hits / $n).'%';
    }
}

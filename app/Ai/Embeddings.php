<?php

namespace App\Ai;

use Illuminate\Support\Facades\DB;

/**
 * Text vectors for similarity: the provider's embedding model when AI is on, or a
 * lexical fallback (hashed word and word-pair counts) when it is off or fails.
 *
 * Vectors from different models are never compared: every batch is computed with
 * one model, and provider vectors are stored per (owner, model) with a hash of the
 * text, so an unchanged profile is never embedded twice.
 */
class Embeddings
{
    public const LEXICAL = 'lexical-v1';

    private const DIMENSIONS = 512;

    public function __construct(private readonly LlmClient $llm)
    {
    }

    /**
     * Vectors for several owners at once, all from the same model.
     *
     * @param  array<string, array{type: string, id: int, text: string}>  $items  keyed by any caller key
     * @return array{model: string, vectors: array<string, list<float>>}
     */
    public function forOwners(array $items, string $feature = 'embeddings'): array
    {
        if ($items === []) {
            return ['model' => self::LEXICAL, 'vectors' => []];
        }

        if ($this->llm->isLive()) {
            try {
                return ['model' => $this->llm->embeddingModel(), 'vectors' => $this->provider($items, $feature)];
            } catch (AiException) {
                // fall through to lexical vectors for the whole batch
            }
        }

        $vectors = [];
        foreach ($items as $key => $item) {
            $vectors[$key] = self::lexical($item['text']);
        }

        return ['model' => self::LEXICAL, 'vectors' => $vectors];
    }

    public static function cosine(array $a, array $b): float
    {
        $dot = $na = $nb = 0.0;
        foreach ($a as $i => $x) {
            $y = $b[$i] ?? 0.0;
            $dot += $x * $y;
            $na += $x * $x;
            $nb += $y * $y;
        }

        return ($na > 0 && $nb > 0) ? $dot / (sqrt($na) * sqrt($nb)) : 0.0;
    }

    /** Hashed bag of words and word pairs, L2-normalised. Works for Bangla as well as English. */
    public static function lexical(string $text): array
    {
        $vec = array_fill(0, self::DIMENSIONS, 0.0);
        $words = array_values(array_filter(
            preg_split('/[^\p{L}\p{N}]+/u', mb_strtolower($text)) ?: [],
            fn ($w) => mb_strlen($w) > 2 && ! in_array($w, self::STOP, true)
        ));
        $terms = $words;
        for ($i = 1; $i < count($words); $i++) {
            $terms[] = $words[$i - 1].' '.$words[$i];
        }
        foreach ($terms as $term) {
            $h = crc32($term);
            $vec[$h % self::DIMENSIONS] += ($h & 1) ? 1.0 : -1.0;
        }
        $norm = sqrt(array_sum(array_map(fn ($x) => $x * $x, $vec)));

        return $norm > 0 ? array_map(fn ($x) => $x / $norm, $vec) : $vec;
    }

    /** @return array<string, list<float>> */
    private function provider(array $items, string $feature): array
    {
        $model = $this->llm->embeddingModel();
        $stored = DB::table('embeddings')->where('model', $model)
            ->where(function ($q) use ($items) {
                foreach ($items as $item) {
                    $q->orWhere(fn ($w) => $w->where('owner_type', $item['type'])->where('owner_id', $item['id']));
                }
            })
            ->get()
            ->keyBy(fn ($row) => $row->owner_type.':'.$row->owner_id);

        $vectors = [];
        $missing = [];
        foreach ($items as $key => $item) {
            $hash = hash('sha256', $item['text']);
            $row = $stored->get($item['type'].':'.$item['id']);
            if ($row && $row->text_hash === $hash) {
                $vectors[$key] = array_values(unpack('g*', $row->vector));
            } else {
                $missing[$key] = $item + ['hash' => $hash];
            }
        }

        if ($missing !== []) {
            $fresh = $this->llm->embed($feature, array_column($missing, 'text'));
            foreach (array_keys($missing) as $i => $key) {
                $item = $missing[$key];
                $vectors[$key] = $fresh[$i];
                DB::table('embeddings')->updateOrInsert(
                    ['owner_type' => $item['type'], 'owner_id' => $item['id'], 'model' => $model],
                    ['text_hash' => $item['hash'], 'vector' => pack('g*', ...$fresh[$i]), 'updated_at' => now(), 'created_at' => now()],
                );
            }
        }

        return $vectors;
    }

    private const STOP = [
        'the', 'and', 'for', 'with', 'who', 'can', 'are', 'you', 'your', 'our', 'his', 'her', 'she', 'him', 'has',
        'have', 'from', 'that', 'this', 'will', 'need', 'needs', 'looking', 'want', 'prefer', 'someone', 'tutor',
        'student', 'class', 'help', 'also', 'very', 'all', 'any', 'not', 'but',
    ];
}

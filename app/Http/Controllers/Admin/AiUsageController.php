<?php

namespace App\Http\Controllers\Admin;

use App\Ai\LlmClient;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

/** What the AI features cost and how they behave: calls, failures, latency, cache hits. */
class AiUsageController extends Controller
{
    public function __invoke(LlmClient $llm)
    {
        $since = now()->subDays(7);
        $rows = DB::table('ai_calls')->where('created_at', '>=', $since)->get();

        $byFeature = $rows->groupBy('feature')->map(function ($calls, $feature) {
            $live = $calls->where('cached', false);
            $latencies = $live->where('ok', true)->pluck('latency_ms')->sort()->values();
            $pct = fn (float $p) => $latencies->isEmpty() ? null : $latencies[(int) floor(($latencies->count() - 1) * $p)];

            return [
                'feature' => $feature,
                'calls' => $calls->count(),
                'cached' => $calls->where('cached', true)->count(),
                'failed' => $calls->where('ok', false)->count(),
                'p50' => $pct(0.5),
                'p95' => $pct(0.95),
                'tokens' => (int) $live->sum('input_tokens') + (int) $live->sum('output_tokens'),
            ];
        })->sortByDesc('calls')->values();

        $recentErrors = DB::table('ai_calls')->where('ok', false)->latest('id')->limit(10)->get();

        return view('admin.ai', [
            'live' => $llm->isLive(),
            'chatModel' => $llm->chatModel(),
            'embeddingModel' => $llm->embeddingModel(),
            'byFeature' => $byFeature,
            'total' => $rows->count(),
            'recentErrors' => $recentErrors,
        ]);
    }
}

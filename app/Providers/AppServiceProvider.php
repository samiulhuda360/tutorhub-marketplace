<?php

namespace App\Providers;

use App\Ai\CallLog;
use App\Ai\LlmClient;
use App\Ai\OfflineClient;
use App\Ai\OpenAiCompatibleClient;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(LlmClient::class, function ($app) {
            $c = config('ai');
            $live = $c['driver'] === 'live' || ($c['driver'] === 'auto' && filled($c['api_key']));

            return $live
                ? new OpenAiCompatibleClient($c['base_url'], (string) $c['api_key'], $c['chat_model'],
                    $c['embedding_model'], $c['timeout'], $c['cache_days'], $app->make(CallLog::class))
                : new OfflineClient();
        });
    }

    public function boot(): void
    {
        // AI endpoints cost provider quota: 15 requests a minute per signed-in user.
        RateLimiter::for('ai', fn (Request $request) => Limit::perMinute(15)->by($request->user()?->id ?: $request->ip()));
    }
}

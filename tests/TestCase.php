<?php

namespace Tests;

use App\Ai\CallLog;
use App\Ai\LlmClient;
use App\Ai\OpenAiCompatibleClient;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Admin pages load Tailwind through Vite; the tests do not build front-end assets.
        $this->withoutVite();
    }

    protected function admin(): User
    {
        return User::where('email', 'admin@tutorhub.test')->firstOrFail();
    }

    protected function parentUser(int $n = 1): User
    {
        return User::where('email', "parent{$n}@example.com")->firstOrFail();
    }

    protected function tutor(string $name): User
    {
        return User::where('role', 'tutor')->where('name', $name)->firstOrFail();
    }

    /**
     * Swap in the real OpenAI-compatible client, pointed at a fake provider.
     *
     * @param  list<array|string|int>  $chat  replies in order: an array is sent as the JSON message
     *                                        content, a string as plain text, an int as an HTTP error status
     * @param  (callable(list<string>): list<list<float>>)|null  $embed
     */
    protected function fakeAi(array $chat = [], ?callable $embed = null): void
    {
        Http::fake(function (Request $request) use (&$chat, $embed) {
            if (str_ends_with($request->url(), '/embeddings')) {
                $vectors = $embed ? $embed($request['input']) : array_map(fn () => [1.0, 0.0], $request['input']);

                return Http::response(['data' => array_map(fn ($v, $i) => ['index' => $i, 'embedding' => $v], $vectors, array_keys($vectors))]);
            }
            $next = array_shift($chat);
            if (is_int($next)) {
                return Http::response(['error' => 'fake'], $next);
            }
            $content = is_array($next) ? json_encode($next) : (string) $next;

            return Http::response([
                'choices' => [['message' => ['content' => $content]]],
                'usage' => ['prompt_tokens' => 100, 'completion_tokens' => 20],
            ]);
        });

        $this->app->instance(LlmClient::class, new OpenAiCompatibleClient(
            'https://ai.test/v1', 'test-key', 'fake-chat', 'fake-embed', 5, 1, new CallLog,
        ));
    }
}

<?php

namespace App\Services\AiProviders;

use App\Contracts\AiProviderInterface;
use App\Exceptions\RateLimitExceededException;
use App\Services\AiProviders\Concerns\ParsesArticleJson;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class GroqProvider implements AiProviderInterface
{
    use ParsesArticleJson;

    public function generate(string $systemPrompt, string $userPrompt): array
    {
        $apiKey = config('services.groq.key');

        if (! $apiKey) {
            throw new RuntimeException('GROQ_API_KEY belum dikonfigurasi.');
        }

        $response = Http::withToken($apiKey)
            ->timeout(60)
            ->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => config('services.groq.model', 'llama-3.3-70b-versatile'),
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $userPrompt],
                ],
            ]);

        if ($response->status() === 429) {
            throw new RateLimitExceededException('groq', $response->body());
        }

        if (! $response->successful()) {
            throw new RuntimeException("Groq API error ({$response->status()}): {$response->body()}");
        }

        $text = $response->json('choices.0.message.content');

        if (! is_string($text) || trim($text) === '') {
            throw new RuntimeException('Respons Groq kosong atau tidak sesuai format yang diharapkan.');
        }

        return $this->parseArticleJson($text);
    }
}

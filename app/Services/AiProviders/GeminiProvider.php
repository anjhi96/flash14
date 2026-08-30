<?php

namespace App\Services\AiProviders;

use App\Contracts\AiProviderInterface;
use App\Exceptions\RateLimitExceededException;
use App\Services\AiProviders\Concerns\ParsesArticleJson;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class GeminiProvider implements AiProviderInterface
{
    use ParsesArticleJson;

    public function generate(string $systemPrompt, string $userPrompt): array
    {
        $apiKey = config('services.gemini.key');

        if (! $apiKey) {
            throw new RuntimeException('GEMINI_API_KEY belum dikonfigurasi.');
        }

        $model = config('services.gemini.model', 'gemini-2.5-flash');
        $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";

        $response = Http::timeout(60)->post($url, [
            'systemInstruction' => [
                'parts' => [['text' => $systemPrompt]],
            ],
            'contents' => [
                ['role' => 'user', 'parts' => [['text' => $userPrompt]]],
            ],
        ]);

        if ($response->status() === 429) {
            throw new RateLimitExceededException('gemini', $response->body());
        }

        if (! $response->successful()) {
            throw new RuntimeException("Gemini API error ({$response->status()}): {$response->body()}");
        }

        $text = $response->json('candidates.0.content.parts.0.text');

        if (! is_string($text) || trim($text) === '') {
            throw new RuntimeException('Respons Gemini kosong atau tidak sesuai format yang diharapkan.');
        }

        return $this->parseArticleJson($text);
    }
}

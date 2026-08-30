<?php

namespace App\Services\AiProviders\Concerns;

use Illuminate\Support\Str;
use RuntimeException;

/**
 * Shared by every AiProviderInterface implementation so the "models don't
 * always follow the JSON-only instruction perfectly" tolerance lives in one
 * place instead of being duplicated per provider.
 */
trait ParsesArticleJson
{
    /**
     * @return array{title: string, content: string, excerpt: string}
     *
     * @throws RuntimeException if the text isn't valid JSON or is missing required keys.
     */
    private function parseArticleJson(string $text): array
    {
        // Strip a ```json ... ``` fence if the model wrapped its output despite instructions.
        $cleaned = trim($text);
        $cleaned = preg_replace('/^```(?:json)?\s*|\s*```$/', '', $cleaned) ?? $cleaned;

        $decoded = json_decode($cleaned, true);

        if (! is_array($decoded)) {
            throw new RuntimeException('Respons AI bukan JSON yang valid: '.Str::limit($text, 300));
        }

        foreach (['title', 'content', 'excerpt'] as $key) {
            if (empty($decoded[$key]) || ! is_string($decoded[$key])) {
                throw new RuntimeException("Field '{$key}' hilang atau kosong pada respons AI.");
            }
        }

        return [
            'title' => $decoded['title'],
            'content' => $decoded['content'],
            'excerpt' => $decoded['excerpt'],
        ];
    }
}

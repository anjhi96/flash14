<?php

namespace App\Contracts;

use App\Exceptions\RateLimitExceededException;
use RuntimeException;

/**
 * Contract for a rewrite-news-into-an-article AI backend. Adding a new
 * provider (e.g. going back to Claude, or trying another model) only
 * requires a new class implementing this interface — no changes needed to
 * ArticleGeneratorService's orchestration/fallback logic.
 */
interface AiProviderInterface
{
    /**
     * @return array{title: string, content: string, excerpt: string}
     *
     * @throws RateLimitExceededException when the provider itself is rate-limited (HTTP 429).
     * @throws RuntimeException on any other failure (network error, bad response, invalid JSON).
     */
    public function generate(string $systemPrompt, string $userPrompt): array;
}

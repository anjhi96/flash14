<?php

namespace App\Exceptions;

use RuntimeException;

/**
 * Thrown by an AiProviderInterface implementation when the provider returns
 * a rate-limit response (HTTP 429) — the orchestrator (ArticleGeneratorService)
 * catches this specifically to move on to the next provider instead of
 * treating it as a hard failure.
 */
class RateLimitExceededException extends RuntimeException
{
    public function __construct(public readonly string $provider, string $message = '')
    {
        parent::__construct($message !== '' ? $message : "Rate limit terlampaui untuk provider AI [{$provider}].");
    }
}

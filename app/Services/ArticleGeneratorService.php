<?php

namespace App\Services;

use App\Contracts\AiProviderInterface;
use App\Exceptions\RateLimitExceededException;
use App\Services\AiProviders\PromptBuilder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Throwable;

class ArticleGeneratorService
{
    private const ROUND_ROBIN_CACHE_KEY = 'ai-provider-round-robin-index';

    /**
     * @param  array<string, AiProviderInterface>  $providers  Keyed by provider name — must match
     *                                                         the entries in config('services.ai_providers.order').
     */
    public function __construct(private readonly array $providers) {}

    /**
     * Rewrite raw news data into an original article, trying each configured
     * AI provider in turn until one succeeds. A single provider being
     * rate-limited or failing does not fail the whole call — it just moves
     * on to the next one.
     *
     * @param  array{title: string, summary: string, link: string, source_name: string}  $newsData
     * @return array{title: string, content: string, excerpt: string}|null
     */
    public function generate(array $newsData): ?array
    {
        $systemPrompt = PromptBuilder::systemPrompt();
        $userPrompt = PromptBuilder::userPrompt($newsData);

        foreach ($this->resolveProviderOrder() as $name) {
            $provider = $this->providers[$name] ?? null;

            if (! $provider) {
                continue;
            }

            try {
                return $provider->generate($systemPrompt, $userPrompt);
            } catch (RateLimitExceededException $e) {
                Log::warning("ArticleGeneratorService: provider [{$name}] kena rate limit, mencoba provider berikutnya", [
                    'source' => $newsData['link'] ?? null,
                ]);
            } catch (Throwable $e) {
                Log::error("ArticleGeneratorService: provider [{$name}] gagal generate artikel", [
                    'source' => $newsData['link'] ?? null,
                    'message' => $e->getMessage(),
                ]);
            }
        }

        Log::error('ArticleGeneratorService: semua provider AI gagal, artikel dilewati', [
            'source' => $newsData['link'] ?? null,
        ]);

        return null;
    }

    /**
     * Order to try providers in. With the default "fallback" strategy this
     * is just config('services.ai_providers.order') as-is. With
     * "round_robin", the starting provider rotates on every call (persisted
     * via cache) so load spreads evenly across providers instead of always
     * hitting the first one until it's rate-limited — the remaining
     * providers still serve as a fallback chain after the rotated one.
     *
     * @return array<int, string>
     */
    private function resolveProviderOrder(): array
    {
        $order = array_values(array_filter(config('services.ai_providers.order', ['gemini', 'groq'])));

        if ($order === [] || count($order) < 2) {
            return $order;
        }

        if (config('services.ai_providers.strategy', 'fallback') !== 'round_robin') {
            return $order;
        }

        $index = Cache::get(self::ROUND_ROBIN_CACHE_KEY, 0) % count($order);
        Cache::forever(self::ROUND_ROBIN_CACHE_KEY, ($index + 1) % count($order));

        return [...array_slice($order, $index), ...array_slice($order, 0, $index)];
    }
}

<?php

namespace App\Services;

use App\Models\NewsSource;
use App\Models\Post;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class NewsFetcherService
{
    /**
     * Maximum number of <item>s pulled from a single feed per fetch.
     */
    private const ITEMS_PER_FEED = 3;

    /**
     * How long a feed's parsed result is cached before re-fetching.
     */
    private const CACHE_MINUTES = 30;

    /**
     * Fetch the latest headlines from the active NewsSource rows.
     *
     * @param  string|null  $category  Limit to sources with this category; null = all active sources.
     * @return array<int, array{title: string, summary: string, link: string, source_name: string, published_at: Carbon, topic: string}>
     */
    public function fetchLatest(?string $category = null): array
    {
        $sources = NewsSource::active()
            ->when($category !== null, fn ($q) => $q->where('category', $category))
            ->get();

        $items = [];

        foreach ($sources as $source) {
            if ($source->type !== 'rss') {
                Log::warning('NewsFetcherService: source type belum didukung, dilewati', [
                    'source_id' => $source->id,
                    'type' => $source->type,
                ]);

                continue;
            }

            foreach ($this->fetchFeed($source->url) as $item) {
                $items[] = [...$item, 'topic' => $source->category ?? 'umum'];
            }

            $source->update(['last_fetched_at' => now()]);
        }

        return $items;
    }

    /**
     * A news item is a duplicate if its source URL was already saved as a Post.
     */
    public function isDuplicate(string $sourceUrl): bool
    {
        return Post::where('source_url', $sourceUrl)->exists();
    }

    /**
     * Fetch a single feed URL fresh (no cache) for the admin "Test Fetch"
     * preview — works even for a URL not yet saved as a NewsSource.
     *
     * @return array<int, array{title: string, summary: string, link: string, source_name: string, published_at: Carbon}>
     */
    public function testFetch(string $url, int $limit = 5): array
    {
        try {
            $response = Http::timeout(10)->get($url);

            if (! $response->successful()) {
                return [];
            }

            return array_slice($this->parseFeed($response->body(), $url, $limit), 0, $limit);
        } catch (\Throwable $e) {
            Log::warning('NewsFetcherService: test fetch threw an exception', [
                'url' => $url,
                'message' => $e->getMessage(),
            ]);

            return [];
        }
    }

    /**
     * Fetch and parse a single RSS feed, cached briefly to avoid hammering
     * the source on repeated command runs within a short window.
     *
     * @return array<int, array{title: string, summary: string, link: string, source_name: string, published_at: Carbon}>
     */
    private function fetchFeed(string $url): array
    {
        $cacheKey = 'rss-feed:'.md5($url);

        return Cache::remember($cacheKey, now()->addMinutes(self::CACHE_MINUTES), function () use ($url) {
            try {
                $response = Http::timeout(10)->get($url);

                if (! $response->successful()) {
                    Log::warning('NewsFetcherService: feed request failed', [
                        'url' => $url,
                        'status' => $response->status(),
                    ]);

                    return [];
                }

                return $this->parseFeed($response->body(), $url);
            } catch (\Throwable $e) {
                Log::warning('NewsFetcherService: feed fetch threw an exception', [
                    'url' => $url,
                    'message' => $e->getMessage(),
                ]);

                return [];
            }
        });
    }

    /**
     * @return array<int, array{title: string, summary: string, link: string, source_name: string, published_at: Carbon}>
     */
    private function parseFeed(string $xml, string $url, int $limit = self::ITEMS_PER_FEED): array
    {
        libxml_use_internal_errors(true);
        $feed = simplexml_load_string($xml);
        libxml_use_internal_errors(false);

        if ($feed === false || ! isset($feed->channel->item)) {
            Log::warning('NewsFetcherService: could not parse feed XML', ['url' => $url]);

            return [];
        }

        $sourceName = trim((string) ($feed->channel->title ?? '')) ?: (parse_url($url, PHP_URL_HOST) ?: $url);

        $items = [];

        foreach ($feed->channel->item as $item) {
            if (count($items) >= $limit) {
                break;
            }

            $link = trim((string) $item->link);
            $title = trim((string) $item->title);

            if ($link === '' || $title === '') {
                continue;
            }

            $items[] = [
                'title' => $title,
                'summary' => Str::limit(trim(strip_tags((string) $item->description)), 600),
                'link' => $link,
                'source_name' => $sourceName,
                'published_at' => $this->parseDate((string) $item->pubDate),
            ];
        }

        return $items;
    }

    private function parseDate(string $pubDate): Carbon
    {
        try {
            return $pubDate !== '' ? Carbon::parse($pubDate) : now();
        } catch (\Throwable) {
            return now();
        }
    }
}

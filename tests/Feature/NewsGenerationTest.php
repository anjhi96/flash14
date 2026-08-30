<?php

use App\Jobs\GenerateNewsArticlesJob;
use App\Models\Category;
use App\Models\NewsGenerationRun;
use App\Models\NewsSource;
use App\Models\Post;
use App\Models\TeamMember;
use App\Models\User;
use App\Services\ArticleGeneratorService;
use App\Services\NewsFetcherService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Livewire\Volt\Volt;

function sampleRssXml(string $title = 'Contoh Judul Berita Teknologi', string $link = 'https://fake-feed.test/artikel-contoh'): string
{
    return <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0">
  <channel>
    <title>Fake Tech Feed</title>
    <link>https://fake-feed.test</link>
    <description>Feed uji coba</description>
    <item>
      <title>{$title}</title>
      <link>{$link}</link>
      <description>Ini adalah ringkasan singkat berita teknologi untuk keperluan pengujian otomatis.</description>
      <pubDate>Thu, 01 Jan 2026 10:00:00 GMT</pubDate>
    </item>
  </channel>
</rss>
XML;
}

function geminiArticleResponse(string $title, string $content = "## Pendahuluan\n\nIsi artikel dari Gemini.", string $excerpt = 'Ringkasan dari Gemini.'): array
{
    return [
        'candidates' => [
            ['content' => ['parts' => [['text' => json_encode(compact('title', 'content', 'excerpt'))]]]],
        ],
    ];
}

function groqArticleResponse(string $title, string $content = "## Pendahuluan\n\nIsi artikel dari Groq.", string $excerpt = 'Ringkasan dari Groq.'): array
{
    return [
        'choices' => [
            ['message' => ['content' => json_encode(compact('title', 'content', 'excerpt'))]],
        ],
    ];
}

function fakeGeminiSuccess(string $title = 'Judul Artikel Hasil Gemini'): void
{
    Http::fake(['generativelanguage.googleapis.com/*' => Http::response(geminiArticleResponse($title), 200)]);
}

function fakeGroqSuccess(string $title = 'Judul Artikel Hasil Groq'): void
{
    Http::fake(['api.groq.com/*' => Http::response(groqArticleResponse($title), 200)]);
}

test('NewsFetcherService parses an RSS feed from an active NewsSource', function () {
    NewsSource::create([
        'name' => 'Fake Tech Feed',
        'url' => 'https://fake-feed.test/tech-rss',
        'type' => 'rss',
        'category' => 'tech',
        'is_active' => true,
    ]);
    Http::fake(['fake-feed.test/*' => Http::response(sampleRssXml(), 200)]);

    $items = (new NewsFetcherService)->fetchLatest('tech');

    expect($items)->toHaveCount(1);
    expect($items[0])->toMatchArray([
        'title' => 'Contoh Judul Berita Teknologi',
        'link' => 'https://fake-feed.test/artikel-contoh',
        'source_name' => 'Fake Tech Feed',
        'topic' => 'tech',
    ]);
    expect($items[0]['summary'])->toContain('ringkasan singkat');
});

test('NewsFetcherService ignores inactive sources and stamps last_fetched_at', function () {
    $active = NewsSource::create([
        'name' => 'Active Feed', 'url' => 'https://fake-feed.test/active-rss',
        'type' => 'rss', 'category' => 'tech', 'is_active' => true,
    ]);
    $inactive = NewsSource::create([
        'name' => 'Inactive Feed', 'url' => 'https://fake-feed.test/inactive-rss',
        'type' => 'rss', 'category' => 'tech', 'is_active' => false,
    ]);

    Http::fake(['fake-feed.test/*' => Http::response(sampleRssXml(), 200)]);

    (new NewsFetcherService)->fetchLatest();

    expect($active->fresh()->last_fetched_at)->not->toBeNull();
    expect($inactive->fresh()->last_fetched_at)->toBeNull();
    Http::assertSentCount(1);
});

test('NewsFetcherService detects an already-imported source_url as duplicate', function () {
    $category = Category::create(['name' => 'Berita Teknologi', 'slug' => 'berita-teknologi', 'color' => 'secondary']);
    $author = TeamMember::create(['name' => 'Tim Redaksi FlashDev', 'position' => 'Editorial', 'order' => 1]);

    Post::create([
        'author_id' => $author->id,
        'category_id' => $category->id,
        'title' => 'Artikel Sudah Ada',
        'slug' => 'artikel-sudah-ada',
        'body' => 'Isi artikel.',
        'status' => 'draft',
        'reading_time' => 1,
        'source_url' => 'https://fake-feed.test/sudah-ada',
    ]);

    $fetcher = new NewsFetcherService;

    expect($fetcher->isDuplicate('https://fake-feed.test/sudah-ada'))->toBeTrue();
    expect($fetcher->isDuplicate('https://fake-feed.test/belum-ada'))->toBeFalse();
});

test('NewsFetcherService::testFetch previews a feed without persisting anything', function () {
    Http::fake(['fake-feed.test/*' => Http::response(sampleRssXml(), 200)]);

    $items = (new NewsFetcherService)->testFetch('https://fake-feed.test/preview-rss');

    expect($items)->toHaveCount(1);
    expect($items[0]['title'])->toBe('Contoh Judul Berita Teknologi');
    expect(NewsSource::count())->toBe(0);
    expect(Post::count())->toBe(0);
});

test('ArticleGeneratorService uses the first configured provider (Gemini) when it succeeds', function () {
    config(['services.gemini.key' => 'test-gemini-key']);
    config(['services.groq.key' => 'test-groq-key']);
    config(['services.ai_providers.order' => ['gemini', 'groq']]);
    fakeGeminiSuccess();

    $result = app(ArticleGeneratorService::class)->generate([
        'title' => 'Contoh Judul Berita Teknologi',
        'summary' => 'Ringkasan berita asli.',
        'link' => 'https://fake-feed.test/artikel-contoh',
        'source_name' => 'Fake Tech Feed',
    ]);

    expect($result)->not->toBeNull();
    expect($result['title'])->toBe('Judul Artikel Hasil Gemini');
    Http::assertSentCount(1); // Groq never called — Gemini already succeeded.
});

test('ArticleGeneratorService falls back to Groq when Gemini is rate-limited', function () {
    config(['services.gemini.key' => 'test-gemini-key']);
    config(['services.groq.key' => 'test-groq-key']);
    config(['services.ai_providers.order' => ['gemini', 'groq']]);

    Http::fake([
        'generativelanguage.googleapis.com/*' => Http::response([], 429),
        'api.groq.com/*' => Http::response(groqArticleResponse('Judul Artikel Hasil Groq'), 200),
    ]);

    $result = app(ArticleGeneratorService::class)->generate([
        'title' => 'Judul', 'summary' => 'Ringkasan',
        'link' => 'https://fake-feed.test/artikel', 'source_name' => 'Fake Feed',
    ]);

    expect($result)->not->toBeNull();
    expect($result['title'])->toBe('Judul Artikel Hasil Groq');
});

test('ArticleGeneratorService falls back to Groq when Gemini fails for a non-rate-limit reason', function () {
    config(['services.gemini.key' => 'test-gemini-key']);
    config(['services.groq.key' => 'test-groq-key']);
    config(['services.ai_providers.order' => ['gemini', 'groq']]);

    Http::fake([
        'generativelanguage.googleapis.com/*' => Http::response(['error' => 'internal error'], 500),
        'api.groq.com/*' => Http::response(groqArticleResponse('Judul Artikel Hasil Groq'), 200),
    ]);

    $result = app(ArticleGeneratorService::class)->generate([
        'title' => 'Judul', 'summary' => 'Ringkasan',
        'link' => 'https://fake-feed.test/artikel', 'source_name' => 'Fake Feed',
    ]);

    expect($result)->not->toBeNull();
    expect($result['title'])->toBe('Judul Artikel Hasil Groq');
});

test('ArticleGeneratorService returns null without throwing when every provider fails', function () {
    config(['services.gemini.key' => 'test-gemini-key']);
    config(['services.groq.key' => 'test-groq-key']);
    config(['services.ai_providers.order' => ['gemini', 'groq']]);

    Http::fake([
        'generativelanguage.googleapis.com/*' => Http::response(['candidates' => [
            ['content' => ['parts' => [['text' => 'ini bukan JSON sama sekali']]]],
        ]], 200),
        'api.groq.com/*' => Http::response(['choices' => [
            ['message' => ['content' => 'ini juga bukan JSON']],
        ]], 200),
    ]);

    $result = app(ArticleGeneratorService::class)->generate([
        'title' => 'Judul', 'summary' => 'Ringkasan',
        'link' => 'https://fake-feed.test/artikel', 'source_name' => 'Fake Feed',
    ]);

    expect($result)->toBeNull();
});

test('ArticleGeneratorService returns null when no provider has an API key configured', function () {
    config(['services.gemini.key' => null]);
    config(['services.groq.key' => null]);
    config(['services.ai_providers.order' => ['gemini', 'groq']]);

    $result = app(ArticleGeneratorService::class)->generate([
        'title' => 'Judul', 'summary' => 'Ringkasan',
        'link' => 'https://fake-feed.test/artikel', 'source_name' => 'Fake Feed',
    ]);

    expect($result)->toBeNull();
});

test('round_robin strategy rotates which provider is tried first on each call', function () {
    config(['services.gemini.key' => 'test-gemini-key']);
    config(['services.groq.key' => 'test-groq-key']);
    config(['services.ai_providers.order' => ['gemini', 'groq']]);
    config(['services.ai_providers.strategy' => 'round_robin']);
    Cache::forget('ai-provider-round-robin-index');

    Http::fake([
        'generativelanguage.googleapis.com/*' => Http::response(geminiArticleResponse('G1'), 200),
        'api.groq.com/*' => Http::response(groqArticleResponse('Q1'), 200),
    ]);

    $service = app(ArticleGeneratorService::class);
    $newsData = ['title' => 'Judul', 'summary' => 'Ringkasan', 'link' => 'https://fake-feed.test/a', 'source_name' => 'Fake'];

    $first = $service->generate($newsData);
    $second = $service->generate($newsData);

    expect($first['title'])->toBe('G1'); // round 0: gemini first
    expect($second['title'])->toBe('Q1'); // round 1: groq first
});

test('news:generate command saves a new draft post and skips it on a second run', function () {
    NewsSource::create([
        'name' => 'Fake Tech Feed', 'url' => 'https://fake-feed.test/tech-rss',
        'type' => 'rss', 'category' => 'tech', 'is_active' => true,
    ]);
    config(['services.gemini.key' => 'test-gemini-key']);

    Http::fake([
        'fake-feed.test/*' => Http::response(sampleRssXml(), 200),
        'generativelanguage.googleapis.com/*' => Http::response(geminiArticleResponse('Judul Artikel Hasil Tulis Ulang AI'), 200),
    ]);

    $this->artisan('news:generate', ['--topic' => 'tech', '--limit' => 1])
        ->assertSuccessful();

    $this->assertDatabaseCount('posts', 1);

    $post = Post::first();
    expect($post->status)->toBe('draft');
    expect($post->is_ai_generated)->toBeTrue();
    expect($post->source_url)->toBe('https://fake-feed.test/artikel-contoh');
    expect($post->source_name)->toBe('Fake Tech Feed');
    expect($post->author?->name)->toBe('Tim Redaksi FlashDev');
    expect($post->category?->slug)->toBe('berita-teknologi');

    $run = NewsGenerationRun::first();
    expect($run->triggered_by)->toBe('scheduler');
    expect($run->status)->toBe('success');
    expect($run->articles_created)->toBe(1);

    // Second run sees the same feed item, already imported — must not duplicate it.
    $this->artisan('news:generate', ['--topic' => 'tech', '--limit' => 1])
        ->assertSuccessful();

    $this->assertDatabaseCount('posts', 1);
    expect(NewsGenerationRun::count())->toBe(2);
});

test('GenerateNewsArticlesJob tolerates a single bad AI call without failing the whole run', function () {
    NewsSource::create([
        'name' => 'Fake Feed', 'url' => 'https://fake-feed.test/tech-rss',
        'type' => 'rss', 'category' => 'tech', 'is_active' => true,
    ]);

    // No AI provider configured at all — every provider fails inside
    // ArticleGeneratorService itself, so the run as a whole should still
    // finish successfully with 0 articles created.
    config(['services.gemini.key' => null]);
    config(['services.groq.key' => null]);
    Http::fake(['fake-feed.test/*' => Http::response(sampleRssXml(), 200)]);

    (new GenerateNewsArticlesJob('tech', 1, 'manual'))->handle(
        app(NewsFetcherService::class),
        app(ArticleGeneratorService::class)
    );

    $run = NewsGenerationRun::first();
    expect($run->triggered_by)->toBe('manual');
    expect($run->status)->toBe('success');
    expect($run->articles_created)->toBe(0);
    expect(Post::count())->toBe(0);
});

test('GenerateNewsArticlesJob records a failed run when something throws unexpectedly', function () {
    $fetcher = Mockery::mock(NewsFetcherService::class);
    $fetcher->shouldReceive('fetchLatest')->andThrow(new RuntimeException('simulated failure'));

    (new GenerateNewsArticlesJob('tech', 1, 'manual'))->handle(
        $fetcher,
        app(ArticleGeneratorService::class)
    );

    $run = NewsGenerationRun::first();
    expect($run->triggered_by)->toBe('manual');
    expect($run->status)->toBe('failed');
    expect($run->error_message)->toBe('simulated failure');
    expect(Post::count())->toBe(0);
});

test('admin can manage news sources through the news-sources-manager component', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $this->actingAs($admin);

    Volt::test('admin.news-sources-manager')
        ->set('sourceName', 'Sumber Uji Coba')
        ->set('sourceUrl', 'https://fake-feed.test/uji-rss')
        ->set('sourceCategory', 'tech')
        ->call('saveSource')
        ->assertHasNoErrors();

    $source = NewsSource::firstWhere('name', 'Sumber Uji Coba');
    expect($source)->not->toBeNull();

    Http::fake(['fake-feed.test/*' => Http::response(sampleRssXml(), 200)]);

    Volt::test('admin.news-sources-manager')
        ->call('testFetch', $source->url, $source->name)
        ->assertSet('showTestFetchModal', true)
        ->assertSet('testFetchFailed', false);

    Volt::test('admin.news-sources-manager')
        ->call('deleteSource', $source->id);

    expect(NewsSource::find($source->id))->toBeNull();
});

test('admin can view the auto-blog panel and dispatch a manual generation run', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $this->actingAs($admin);

    Queue::fake();

    Volt::test('admin.news-generation-panel')
        ->assertSee('Riwayat Run Terakhir')
        ->call('generateNow')
        ->assertSet('dispatchMessage', fn ($message) => str_contains($message, 'antrean'));

    Queue::assertPushed(GenerateNewsArticlesJob::class, function ($job) {
        return $job->triggeredBy === 'manual';
    });
});

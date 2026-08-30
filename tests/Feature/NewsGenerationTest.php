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
use Illuminate\Http\Client\ConnectionException;
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

function fakeAnthropicSuccess(): void
{
    Http::fake([
        'api.anthropic.com/*' => Http::response([
            'content' => [
                [
                    'type' => 'text',
                    'text' => json_encode([
                        'title' => 'Judul Artikel Hasil Tulis Ulang AI',
                        'content' => "## Pendahuluan\n\nIsi artikel hasil tulis ulang AI untuk keperluan pengujian otomatis.\n\n## Analisis\n\nDampaknya bagi developer Indonesia cukup signifikan.",
                        'excerpt' => 'Ringkasan singkat artikel hasil AI.',
                    ]),
                ],
            ],
        ], 200),
    ]);
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

test('ArticleGeneratorService returns a structured article on a well-formed response', function () {
    config(['services.anthropic.key' => 'test-key']);
    fakeAnthropicSuccess();

    $result = (new ArticleGeneratorService)->generate([
        'title' => 'Contoh Judul Berita Teknologi',
        'summary' => 'Ringkasan berita asli.',
        'link' => 'https://fake-feed.test/artikel-contoh',
        'source_name' => 'Fake Tech Feed',
    ]);

    expect($result)->not->toBeNull();
    expect($result['title'])->toBe('Judul Artikel Hasil Tulis Ulang AI');
    expect($result['content'])->toContain('Pendahuluan');
    expect($result['excerpt'])->toBe('Ringkasan singkat artikel hasil AI.');
});

test('ArticleGeneratorService returns null without throwing on a malformed response', function () {
    config(['services.anthropic.key' => 'test-key']);
    Http::fake([
        'api.anthropic.com/*' => Http::response([
            'content' => [
                ['type' => 'text', 'text' => 'ini bukan JSON sama sekali'],
            ],
        ], 200),
    ]);

    $result = (new ArticleGeneratorService)->generate([
        'title' => 'Judul',
        'summary' => 'Ringkasan',
        'link' => 'https://fake-feed.test/artikel',
        'source_name' => 'Fake Tech Feed',
    ]);

    expect($result)->toBeNull();
});

test('ArticleGeneratorService returns null when the API key is missing', function () {
    config(['services.anthropic.key' => null]);

    $result = (new ArticleGeneratorService)->generate([
        'title' => 'Judul',
        'summary' => 'Ringkasan',
        'link' => 'https://fake-feed.test/artikel',
        'source_name' => 'Fake Tech Feed',
    ]);

    expect($result)->toBeNull();
});

test('news:generate command saves a new draft post and skips it on a second run', function () {
    NewsSource::create([
        'name' => 'Fake Tech Feed', 'url' => 'https://fake-feed.test/tech-rss',
        'type' => 'rss', 'category' => 'tech', 'is_active' => true,
    ]);
    config(['services.anthropic.key' => 'test-key']);

    Http::fake([
        'fake-feed.test/*' => Http::response(sampleRssXml(), 200),
        'api.anthropic.com/*' => Http::response([
            'content' => [[
                'type' => 'text',
                'text' => json_encode([
                    'title' => 'Judul Artikel Hasil Tulis Ulang AI',
                    'content' => "## Pendahuluan\n\nIsi artikel hasil tulis ulang AI.",
                    'excerpt' => 'Ringkasan singkat.',
                ]),
            ]],
        ], 200),
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

    // Simulate the AI request throwing a connection-level exception — this
    // is caught inside ArticleGeneratorService itself, so the run as a
    // whole should still finish successfully with 0 articles created.
    config(['services.anthropic.key' => 'test-key']);
    Http::fake([
        'fake-feed.test/*' => Http::response(sampleRssXml(), 200),
        'api.anthropic.com/*' => fn () => throw new ConnectionException('simulated network failure'),
    ]);

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

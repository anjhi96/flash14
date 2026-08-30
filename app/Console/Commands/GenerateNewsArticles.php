<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\NewsGenerationRun;
use App\Models\Post;
use App\Models\Tag;
use App\Models\TeamMember;
use App\Services\ArticleGeneratorService;
use App\Services\NewsFetcherService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class GenerateNewsArticles extends Command
{
    protected $signature = 'news:generate
        {--topic= : Batasi ke satu kategori sumber berita}
        {--limit=1 : Jumlah artikel maksimum yang diproses dalam satu run}
        {--triggered-by=scheduler : scheduler|manual — cuma dipakai untuk pencatatan riwayat run}';

    protected $description = 'Ambil berita terbaru dari sumber aktif, tulis ulang jadi artikel original via AI, simpan sebagai draft.';

    /**
     * Runs fully synchronously (no queue) so it works on shared hosting
     * without a persistent worker process — triggered either by the cron
     * -> schedule:run -> scheduler path, or directly from the admin panel.
     */
    public function handle(NewsFetcherService $fetcher, ArticleGeneratorService $generator): int
    {
        $topic = $this->option('topic');
        $limit = max(1, (int) $this->option('limit'));
        $triggeredBy = $this->option('triggered-by') === 'manual' ? 'manual' : 'scheduler';

        $run = NewsGenerationRun::create([
            'triggered_by' => $triggeredBy,
            'started_at' => now(),
            'status' => 'running',
        ]);

        try {
            [$author, $category] = $this->ensureBootstrapData();

            $this->info('Mengambil berita terbaru...');
            $newsItems = $fetcher->fetchLatest($topic);

            $newItems = collect($newsItems)
                ->reject(fn (array $item) => $fetcher->isDuplicate($item['link']))
                ->take($limit);

            $created = 0;

            foreach ($newItems as $item) {
                $this->info("Memproses: {$item['title']}");

                $article = $generator->generate($item);

                if ($article === null) {
                    $this->error("Gagal generate artikel untuk: {$item['title']}");
                    Log::error('news:generate: gagal generate artikel dari AI', ['source' => $item['link']]);

                    continue;
                }

                $post = Post::create([
                    'author_id' => $author->id,
                    'category_id' => $category->id,
                    'title' => mb_substr($article['title'], 0, 255),
                    'slug' => $this->uniqueSlug($article['title']),
                    'excerpt' => Str::limit($article['excerpt'], 250, ''),
                    'body' => $article['content'],
                    'status' => 'draft',
                    'is_ai_generated' => true,
                    'source_url' => $item['link'],
                    'source_name' => $item['source_name'],
                    'reading_time' => Post::calculateReadingTime($article['content']),
                ]);

                $post->tags()->sync($this->tagIdsForTopic($item['topic']));

                $created++;
                $this->info("Tersimpan sebagai draft: #{$post->id} - {$post->title}");
                Log::info('news:generate: artikel berhasil disimpan sebagai draft', [
                    'post_id' => $post->id,
                    'source' => $item['link'],
                    'topic' => $item['topic'],
                ]);
            }

            $run->update([
                'finished_at' => now(),
                'items_fetched' => count($newsItems),
                'articles_created' => $created,
                'status' => 'success',
            ]);

            $this->info("Selesai. {$created} artikel baru disimpan sebagai draft.");
            Log::info("news:generate: selesai, {$created} artikel baru disimpan.");
        } catch (Throwable $e) {
            $run->update([
                'finished_at' => now(),
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            $this->error('Run gagal total: '.$e->getMessage());
            Log::error('news:generate: run gagal total', ['message' => $e->getMessage()]);
        }

        return self::SUCCESS;
    }

    /**
     * Ensure the byline & category auto-generated articles are filed under
     * actually exist — author_id/category_id are NOT NULL on posts.
     *
     * @return array{0: TeamMember, 1: Category}
     */
    private function ensureBootstrapData(): array
    {
        $author = TeamMember::firstOrCreate(
            ['name' => 'Tim Redaksi FlashDev'],
            [
                'position' => 'Editorial & Content Automation',
                'bio' => 'Tim redaksi otomatis yang merangkum & menulis ulang berita teknologi terkini untuk pembaca FlashDev.',
                'order' => TeamMember::count() + 1,
            ]
        );

        $category = Category::firstOrCreate(
            ['slug' => 'berita-teknologi'],
            [
                'name' => 'Berita Teknologi',
                'description' => 'Rangkuman berita teknologi, bisnis digital, dan keamanan siber terkini.',
                'color' => 'secondary',
            ]
        );

        return [$author, $category];
    }

    /**
     * @return array<int, int>
     */
    private function tagIdsForTopic(string $topic): array
    {
        $tagName = match ($topic) {
            'security' => 'Cybersecurity',
            'business' => 'Startup',
            'umum' => 'Berita',
            default => Str::title($topic),
        };

        $tag = Tag::firstOrCreate(
            ['slug' => Str::slug($tagName)],
            ['name' => $tagName]
        );

        return [$tag->id];
    }

    private function uniqueSlug(string $title): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $suffix = 2;

        while (Post::where('slug', $slug)->exists()) {
            $slug = "{$base}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }
}

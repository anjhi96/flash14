<?php

namespace App\Console\Commands;

use App\Jobs\GenerateNewsArticlesJob;
use App\Services\ArticleGeneratorService;
use App\Services\NewsFetcherService;
use Illuminate\Console\Command;

class GenerateNewsArticles extends Command
{
    protected $signature = 'news:generate
        {--topic= : Batasi ke satu kategori sumber berita}
        {--limit=1 : Jumlah artikel maksimum yang diproses dalam satu run}';

    protected $description = 'Ambil berita terbaru dari sumber aktif, tulis ulang jadi artikel original via AI, simpan sebagai draft.';

    public function handle(NewsFetcherService $fetcher, ArticleGeneratorService $generator): int
    {
        $topic = $this->option('topic');
        $limit = max(1, (int) $this->option('limit'));

        $this->info('Menjalankan news:generate...');

        // Run synchronously in-process (not ::dispatch()) so the scheduler
        // keeps working exactly as before — no queue worker dependency for
        // the automatic path. The admin "Generate Sekarang" button uses a
        // real ::dispatch() of the same job instead.
        (new GenerateNewsArticlesJob($topic, $limit, 'scheduler'))->handle($fetcher, $generator);

        $this->info('Selesai. Lihat tab Auto-Blog di admin panel untuk riwayat run.');

        return self::SUCCESS;
    }
}

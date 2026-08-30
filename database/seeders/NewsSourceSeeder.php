<?php

namespace Database\Seeders;

use App\Models\NewsSource;
use Illuminate\Database\Seeder;

class NewsSourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sources = [
            [
                'name' => 'TechCrunch',
                'url' => 'https://techcrunch.com/feed/',
                'type' => 'rss',
                'category' => 'tech',
                'is_active' => true,
            ],
            [
                'name' => 'The Verge',
                'url' => 'https://www.theverge.com/rss/index.xml',
                'type' => 'rss',
                'category' => 'tech',
                'is_active' => true,
            ],
            [
                'name' => 'DailySocial',
                'url' => 'https://dailysocial.id/feed',
                'type' => 'rss',
                'category' => 'business',
                'is_active' => true,
            ],
            [
                'name' => 'The Hacker News',
                'url' => 'https://feeds.feedburner.com/TheHackersNews',
                'type' => 'rss',
                'category' => 'security',
                'is_active' => true,
            ],
            [
                'name' => 'Krebs on Security',
                'url' => 'https://krebsonsecurity.com/feed/',
                'type' => 'rss',
                'category' => 'security',
                'is_active' => true,
            ],
        ];

        foreach ($sources as $source) {
            NewsSource::updateOrCreate(['url' => $source['url']], $source);
        }
    }
}

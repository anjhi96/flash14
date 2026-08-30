<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Auto-blog: fetch RSS headlines, rewrite via AI, save as draft (~3x/day, 1 article/run).
Schedule::command('news:generate')->cron('0 */8 * * *');

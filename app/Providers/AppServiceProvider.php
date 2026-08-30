<?php

namespace App\Providers;

use App\Services\AiProviders\GeminiProvider;
use App\Services\AiProviders\GroqProvider;
use App\Services\ArticleGeneratorService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ArticleGeneratorService::class, function ($app) {
            return new ArticleGeneratorService([
                'gemini' => $app->make(GeminiProvider::class),
                'groq' => $app->make(GroqProvider::class),
            ]);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ArticleGeneratorService
{
    private const SYSTEM_PROMPT = <<<'PROMPT'
Anda adalah editor konten teknologi profesional untuk blog agensi software "FlashDev". Tugas Anda menulis ULANG sebuah berita menjadi artikel blog original berbahasa Indonesia — parafrase total dengan kata-kata Anda sendiri, BUKAN menerjemahkan atau menyalin kalimat dari sumber.

Aturan:
- Panjang artikel 400-600 kata.
- Gaya bahasa semi-formal: profesional tapi enak dibaca, sesuai audiens pembaca blog teknologi.
- Strukturkan isi artikel dengan heading Markdown (## dan ###).
- Tutup artikel dengan satu paragraf opini/analisis singkat dari sudut pandang redaksi (misal dampaknya bagi bisnis atau developer di Indonesia) — bukan sekadar mengulang fakta.
- JANGAN menyalin kalimat persis dari sumber asli.
- JANGAN mengarang fakta baru yang tidak ada pada ringkasan sumber yang diberikan.
- Balas HANYA dengan JSON valid, tanpa teks atau markdown fence di luar JSON, persis dengan struktur berikut:
{"title": "judul artikel baru", "content": "isi artikel lengkap dalam format Markdown", "excerpt": "ringkasan 1-2 kalimat"}
PROMPT;

    /**
     * Rewrite raw news data into an original article.
     *
     * @param  array{title: string, summary: string, link: string, source_name: string}  $newsData
     * @return array{title: string, content: string, excerpt: string}|null
     */
    public function generate(array $newsData): ?array
    {
        $apiKey = config('services.anthropic.key');

        if (! $apiKey) {
            Log::error('ArticleGeneratorService: ANTHROPIC_API_KEY is not configured.');

            return null;
        }

        try {
            $response = Http::withHeaders([
                'x-api-key' => $apiKey,
                'anthropic-version' => '2023-06-01',
                'content-type' => 'application/json',
            ])->timeout(60)->post('https://api.anthropic.com/v1/messages', [
                'model' => config('services.anthropic.model'),
                'max_tokens' => 2048,
                'system' => self::SYSTEM_PROMPT,
                'messages' => [
                    ['role' => 'user', 'content' => $this->buildUserPrompt($newsData)],
                ],
            ]);
        } catch (\Throwable $e) {
            Log::error('ArticleGeneratorService: request threw an exception', [
                'source' => $newsData['link'] ?? null,
                'message' => $e->getMessage(),
            ]);

            return null;
        }

        if (! $response->successful()) {
            Log::error('ArticleGeneratorService: Anthropic API returned an error', [
                'source' => $newsData['link'] ?? null,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return null;
        }

        $text = $response->json('content.0.text');

        if (! is_string($text) || trim($text) === '') {
            Log::error('ArticleGeneratorService: empty response text from Anthropic', [
                'source' => $newsData['link'] ?? null,
            ]);

            return null;
        }

        return $this->parseArticleJson($text, $newsData['link'] ?? null);
    }

    private function buildUserPrompt(array $newsData): string
    {
        return implode("\n", [
            'Judul berita asli: '.($newsData['title'] ?? ''),
            'Sumber: '.($newsData['source_name'] ?? ''),
            'Ringkasan/isi berita asli:',
            $newsData['summary'] ?? '',
        ]);
    }

    /**
     * @return array{title: string, content: string, excerpt: string}|null
     */
    private function parseArticleJson(string $text, ?string $sourceLink): ?array
    {
        // Strip a ```json ... ``` fence if the model wrapped its output despite instructions.
        $cleaned = trim($text);
        $cleaned = preg_replace('/^```(?:json)?\s*|\s*```$/', '', $cleaned) ?? $cleaned;

        $decoded = json_decode($cleaned, true);

        if (! is_array($decoded)) {
            Log::error('ArticleGeneratorService: response was not valid JSON', [
                'source' => $sourceLink,
                'raw' => $text,
            ]);

            return null;
        }

        foreach (['title', 'content', 'excerpt'] as $key) {
            if (empty($decoded[$key]) || ! is_string($decoded[$key])) {
                Log::error("ArticleGeneratorService: missing or empty '{$key}' in AI response", [
                    'source' => $sourceLink,
                    'raw' => $text,
                ]);

                return null;
            }
        }

        return [
            'title' => $decoded['title'],
            'content' => $decoded['content'],
            'excerpt' => $decoded['excerpt'],
        ];
    }
}

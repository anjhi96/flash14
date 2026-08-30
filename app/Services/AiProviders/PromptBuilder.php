<?php

namespace App\Services\AiProviders;

/**
 * Single source of truth for the rewrite prompt, shared by every
 * AiProviderInterface implementation so results stay consistent regardless
 * of which model actually generates them.
 */
class PromptBuilder
{
    public static function systemPrompt(): string
    {
        return <<<'PROMPT'
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
    }

    /**
     * @param  array{title?: string, summary?: string, source_name?: string}  $newsData
     */
    public static function userPrompt(array $newsData): string
    {
        return implode("\n", [
            'Judul berita asli: '.($newsData['title'] ?? ''),
            'Sumber: '.($newsData['source_name'] ?? ''),
            'Ringkasan/isi berita asli:',
            $newsData['summary'] ?? '',
        ]);
    }
}

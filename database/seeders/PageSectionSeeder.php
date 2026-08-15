<?php

namespace Database\Seeders;

use App\Models\PageSection;
use Illuminate\Database\Seeder;

class PageSectionSeeder extends Seeder
{
    public function run(): void
    {
        $sections = [
            [
                'page'         => 'home',
                'section_key'  => 'hero',
                'section_name' => 'Hero Banner (Tagline & CTA)',
                'is_enabled'   => true,
                'order'        => 1,
                'settings'     => null,
            ],
            [
                'page'         => 'home',
                'section_key'  => 'metrics',
                'section_name' => 'Metrics Banner (50+ Proyek, 99.9% Uptime, dll.)',
                'is_enabled'   => true,
                'order'        => 2,
                'settings'     => null,
            ],
            [
                'page'         => 'home',
                'section_key'  => 'tech_stack',
                'section_name' => 'Tech Stack Banner',
                'is_enabled'   => true,
                'order'        => 3,
                'settings'     => [
                    'items' => [
                        'Laravel 13',
                        'Livewire 3',
                        'Tailwind CSS v4',
                        'React / Vue.js',
                        'MySQL / PostgreSQL',
                        'Docker & AWS',
                    ],
                ],
            ],
            [
                'page'         => 'home',
                'section_key'  => 'services',
                'section_name' => 'Layanan Spesialis',
                'is_enabled'   => true,
                'order'        => 4,
                'settings'     => null,
            ],
            [
                'page'         => 'home',
                'section_key'  => 'featured_projects',
                'section_name' => 'Portofolio Terpilih (Featured Projects)',
                'is_enabled'   => true,
                'order'        => 5,
                'settings'     => null,
            ],
            [
                'page'         => 'home',
                'section_key'  => 'process',
                'section_name' => 'Alur Pengerjaan 4 Langkah',
                'is_enabled'   => true,
                'order'        => 6,
                'settings'     => null,
            ],
            [
                'page'         => 'home',
                'section_key'  => 'cta',
                'section_name' => 'Banner CTA (Ajakan Konsultasi)',
                'is_enabled'   => true,
                'order'        => 7,
                'settings'     => null,
            ],
        ];

        foreach ($sections as $data) {
            PageSection::updateOrCreate(
                ['section_key' => $data['section_key']],
                $data
            );
        }
    }
}

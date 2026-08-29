<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'title' => 'Web Development',
                'slug' => 'web-development',
                'icon' => 'code-bracket',
                'short_description' => 'Bangun website & web app custom berbasis Laravel & React/Vue.',
                'starting_price' => 'Rp 2.900.000',
                'description' => 'Kami mengembangkan aplikasi web modern yang cepat, aman, responsif, dan mudah di-scale menggunakan arsitektur bersih.',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'E-Commerce & Online Store',
                'slug' => 'e-commerce-online-store',
                'icon' => 'shopping-cart',
                'short_description' => 'Solusi toko online lengkap dengan integrasi payment gateway.',
                'starting_price' => 'Rp 5.900.000',
                'description' => 'Solusi e-commerce dengan sistem stok otomatis, checkout efisien, integrasi pembayaran digital & perhitungan ongkir.',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Custom Web App / SaaS',
                'slug' => 'custom-web-app-saas',
                'icon' => 'cpu-chip',
                'short_description' => 'Sistem manajemen internal (ERP, CRM, HRIS) & platform SaaS.',
                'starting_price' => 'Rp 12.500.000',
                'description' => 'Otomatisasi bisnis Anda dengan sistem custom yang dibuat khusus sesuai kebutuhan operasional perusahaan.',
                'order' => 3,
                'is_active' => true,
            ],
            [
                'title' => 'UI/UX & Design System',
                'slug' => 'ui-ux-design-system',
                'icon' => 'paint-brush',
                'short_description' => 'Desain antarmuka menarik, modern, dan berfokus pada conversion rate.',
                'starting_price' => 'Rp 2.400.000',
                'description' => 'Proses riset pengguna, wireframing, prototipe interaktif hingga pembuatan design system konsisten.',
                'order' => 4,
                'is_active' => true,
            ],
            [
                'title' => 'Maintenance & Performance Tuning',
                'slug' => 'maintenance-performance-tuning',
                'icon' => 'wrench-screwdriver',
                'short_description' => 'Optimasi kecepatan, keamanan server, dan dukungan teknis 24/7.',
                'starting_price' => 'Rp 1.490.000/bln',
                'description' => 'Perawatan rutin, peningkatan kecepatan load (Lighthouse score 90+), dan backup berkala untuk kedamaian pikiran Anda.',
                'order' => 5,
                'is_active' => true,
            ],
        ];

        foreach ($services as $serviceData) {
            Service::updateOrCreate(
                ['slug' => $serviceData['slug']],
                $serviceData
            );
        }
    }
}

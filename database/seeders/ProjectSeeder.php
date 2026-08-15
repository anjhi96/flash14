<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = [
            [
                'title' => 'NexaMart - E-Commerce Platform',
                'slug' => 'nexamart-ecommerce-platform',
                'client' => 'PT Nexa Retail Indonesia',
                'category' => 'E-Commerce',
                'description' => 'Platform toko online B2C dengan integrasi payment gateway Midtrans, perhitungan ongkir otomatis RajaOngkir, dan dashboard analytics real-time.',
                'thumbnail' => 'https://images.unsplash.com/photo-1556742049-0a67daf40955?auto=format&fit=crop&w=800&q=80',
                'gallery' => json_encode([
                    'https://images.unsplash.com/photo-1556742049-0a67daf40955?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=800&q=80'
                ]),
                'project_url' => 'https://example.com/nexamart',
                'is_featured' => true,
                'order' => 1,
            ],
            [
                'title' => 'OmniCare - Hospital Management System',
                'slug' => 'omnicare-hospital-management-system',
                'client' => 'Klinik & Rumah Sakit OmniCare',
                'category' => 'Custom Web App',
                'description' => 'Sistem reservasi dokter, rekam medis elektronik (EMR), serta manajemen inventaris apotek terpadu berbasis cloud.',
                'thumbnail' => 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?auto=format&fit=crop&w=800&q=80',
                'gallery' => json_encode([
                    'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?auto=format&fit=crop&w=800&q=80'
                ]),
                'project_url' => 'https://example.com/omnicare',
                'is_featured' => true,
                'order' => 2,
            ],
            [
                'title' => 'Zenith Holding - Corporate Profile',
                'slug' => 'zenith-holding-corporate-profile',
                'client' => 'Zenith Global Group',
                'category' => 'Company Profile',
                'description' => 'Website profil perusahaan investasi dengan desain ultra-modern, animasi interaktif, serta dukungan multi-bahasa.',
                'thumbnail' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=800&q=80',
                'gallery' => json_encode([
                    'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=800&q=80'
                ]),
                'project_url' => 'https://example.com/zenith',
                'is_featured' => true,
                'order' => 3,
            ],
            [
                'title' => 'PulseFlow - Project Management SaaS',
                'slug' => 'pulseflow-project-management-saas',
                'client' => 'Pulse Tech Lab',
                'category' => 'SaaS App',
                'description' => 'Aplikasi SaaS kolaborasi tim dengan fitur Kanban board, time tracking, laporan otomatis, dan integrasi Slack.',
                'thumbnail' => 'https://images.unsplash.com/photo-1507238691740-187a5b1d37b8?auto=format&fit=crop&w=800&q=80',
                'gallery' => json_encode([
                    'https://images.unsplash.com/photo-1507238691740-187a5b1d37b8?auto=format&fit=crop&w=800&q=80'
                ]),
                'project_url' => 'https://example.com/pulseflow',
                'is_featured' => false,
                'order' => 4,
            ],
            [
                'title' => 'EduPro - Learning Management System',
                'slug' => 'edupro-learning-management-system',
                'client' => 'Yayasan Pendidikan Nusantara',
                'category' => 'Custom Web App',
                'description' => 'Platform kursus online dengan fitur video streaming, kuis interaktif, sertifikat otomatis, dan sistem forum diskusi.',
                'thumbnail' => 'https://images.unsplash.com/photo-1501504905252-473c47e087f8?auto=format&fit=crop&w=800&q=80',
                'gallery' => json_encode([
                    'https://images.unsplash.com/photo-1501504905252-473c47e087f8?auto=format&fit=crop&w=800&q=80'
                ]),
                'project_url' => 'https://example.com/edupro',
                'is_featured' => false,
                'order' => 5,
            ],
        ];

        foreach ($projects as $p) {
            Project::updateOrCreate(
                ['slug' => $p['slug']],
                $p
            );
        }
    }
}

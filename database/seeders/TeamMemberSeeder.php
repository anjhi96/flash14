<?php

namespace Database\Seeders;

use App\Models\TeamMember;
use Illuminate\Database\Seeder;

class TeamMemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $members = [
            [
                'name' => 'Budi Santoso',
                'position' => 'CEO & Lead Solution Architect',
                'photo' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=400&q=80',
                'bio' => 'Berpengalaman 10+ tahun merancang arsitektur sistem berbasis cloud & aplikasi skala besar.',
                'linkedin_url' => 'https://linkedin.com/in/example-budi',
                'order' => 1,
            ],
            [
                'name' => 'Siti Rahmawati',
                'position' => 'Head of Design (UI/UX)',
                'photo' => 'https://images.unsplash.com/photo-1580489944761-15a19d654956?auto=format&fit=crop&w=400&q=80',
                'bio' => 'Spesialis dalam merancang antarmuka intuitif, riset pengguna, serta design system modern.',
                'linkedin_url' => 'https://linkedin.com/in/example-siti',
                'order' => 2,
            ],
            [
                'name' => 'Rian Hidayat',
                'position' => 'Senior Fullstack Web Developer',
                'photo' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=400&q=80',
                'bio' => 'Ahli dalam pengembangan Laravel, React, Vue, dan optimasi performa web tinggi.',
                'linkedin_url' => 'https://linkedin.com/in/example-rian',
                'order' => 3,
            ],
            [
                'name' => 'Nadia Putri',
                'position' => 'Project Manager & Client Success',
                'photo' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=400&q=80',
                'bio' => 'Memastikan proyek selesai tepat waktu, sesuai anggaran, dan mencapai ekspetasi klien.',
                'linkedin_url' => 'https://linkedin.com/in/example-nadia',
                'order' => 4,
            ],
        ];

        foreach ($members as $m) {
            TeamMember::updateOrCreate(
                ['name' => $m['name']],
                $m
            );
        }
    }
}

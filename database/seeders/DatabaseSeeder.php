<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@example.com')],
            [
                'name' => env('ADMIN_NAME', 'Admin'),
                'password' => bcrypt(env('ADMIN_PASSWORD', 'change-me-immediately')),
                'role' => 'admin',
            ]
        );

        $this->call([
            ServiceSeeder::class,
            ProjectSeeder::class,
            TeamMemberSeeder::class,
            PageSectionSeeder::class,
            BlogSeeder::class,
        ]);
    }
}

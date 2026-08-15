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
            ['email' => 'hallo.flashdev@flash14.id'],
            [
                'name' => 'FlashDev',
                'password' => bcrypt('Anji#8783'),
                'role' => 'admin',
            ]
        );

        $this->call([
            ServiceSeeder::class,
            ProjectSeeder::class,
            TeamMemberSeeder::class,
            PageSectionSeeder::class,
        ]);
    }
}

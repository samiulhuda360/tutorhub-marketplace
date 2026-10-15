<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Lookup tables used everywhere: categories, Dhaka areas, subjects, levels.
        $this->call([
            CategorySeeder::class,
            AreaSeeder::class,
            DhakaSubLocationsSeeder::class,
            SubjectsSeeder::class,
            LevelsSeeder::class,
        ]);

        User::firstOrCreate(['email' => 'admin@tutorhub.test'], [
            'name' => 'TutorHub Admin',
            'password' => Hash::make('Admin@1234'),
            'role' => 'admin',
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        // Demo tutors, parents, offers and matches (all made up).
        $this->call(DemoSeeder::class);

        $this->command?->newLine();
        $this->command?->line('Demo logins (password "password" unless noted):');
        $this->command?->line('  Admin   admin@tutorhub.test / Admin@1234');
        $this->command?->line('  Parent  parent1@example.com ... parent6@example.com');
        $this->command?->line('  Tutor   tutor1@example.com ... tutor22@example.com');
    }
}

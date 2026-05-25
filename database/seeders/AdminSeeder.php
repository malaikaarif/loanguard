<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure admin exists
        User::firstOrCreate(
            ['email' => 'admin@loanguard.com'],
            [
                'name'     => 'Admin',
                'password' => Hash::make('password'),
                'role'     => 'admin',
            ]
        );

        // Create 10 applicant users if needed
        for ($i = 1; $i <= 10; $i++) {
            User::firstOrCreate(
                ['email' => "applicant{$i}@loanguard.com"],
                [
                    'name'     => "Applicant {$i}",
                    'password' => Hash::make('password'),
                    'role'     => 'applicant',
                ]
            );
        }
    }
}
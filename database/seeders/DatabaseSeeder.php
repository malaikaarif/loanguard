<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::updateOrCreate(
            ['email' => 'admin@loanguard.com'],
            [
                'name'     => 'Admin',
                'password' => Hash::make('password'),
                'role'     => 'admin',
            ]
        );

        // Real Pakistani applicants
        $users = [
            ['name' => 'Zimal',      'email' => 'zimal@gmail.com'],
            ['name' => 'Noor Fatima',    'email' => 'noorfatima@gmail.com'],
            ['name' => 'Iqra Safdar',    'email' => 'iqrasafdar@gmail.com'],
            ['name' => 'Aleena Amjad',   'email' => 'aleenaamjad@gmail.com'],
            ['name' => 'Alif',       'email' => 'alif@gmail.com'],
            ['name' => 'Waleed Ahmed',   'email' => 'waleedahmed@gmail.com'],
            ['name' => 'Umer Hayat',     'email' => 'umerhayat@gmail.com'],
            ['name' => 'Sadaf Arif',     'email' => 'sadafarif@gmail.com'],
            ['name' => 'Zumar Yusuf',    'email' => 'zumaryusuf@gmail.com'],
            ['name' => 'Malaika Arif',   'email' => 'malaikaarif098@gmail.com'],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                [
                    'name'     => $user['name'],
                    'password' => Hash::make('password'),
                    'role'     => 'applicant',
                ]
            );
        }
    }
}
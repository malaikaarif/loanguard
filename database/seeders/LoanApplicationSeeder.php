<?php

namespace Database\Seeders;

use App\Models\LoanApplication;
use Illuminate\Database\Seeder;

class LoanApplicationSeeder extends Seeder
{
    public function run(): void
    {
        LoanApplication::factory()->count(50)->create();
    }
}
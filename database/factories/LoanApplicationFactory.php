<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LoanApplicationFactory extends Factory
{
    public function definition(): array
    {
        $creditScore = $this->faker->numberBetween(300, 850);
        $income      = $this->faker->numberBetween(15000, 200000);
        $loanAmount  = $this->faker->numberBetween(5000, 1000000);
        $empYears    = $this->faker->numberBetween(0, 30);

        // Realistic risk scoring
        $score = 0.4;
        if ($creditScore < 580) $score += 0.25;
        if ($income < 30000)    $score += 0.15;
        if ($loanAmount / max($income, 1) > 5) $score += 0.15;
        if ($empYears < 2)      $score += 0.10;
        $score = min($score, 0.99);

        return [
            'user_id'          => User::where('role', 'applicant')->inRandomOrder()->first()?->id ?? 1,
            'loan_amount'      => $loanAmount,
            'income'           => $income,
            'age'              => $this->faker->numberBetween(18, 65),
            'credit_score'     => $creditScore,
            'employment_years' => $empYears,
            'purpose'          => $this->faker->randomElement(['home', 'car', 'education', 'business', 'personal']),
            'risk_score'       => round($score, 4),
            'risk_label'       => $score >= 0.5 ? 'high' : 'low',
            'status'           => $this->faker->randomElement(['pending', 'pending', 'approved', 'rejected']),
        ];
    }
}
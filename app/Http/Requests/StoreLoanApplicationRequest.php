<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLoanApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'applicant';
    }

    public function rules(): array
    {
        return [
            'loan_amount'      => ['required', 'numeric', 'min:1000', 'max:10000000'],
            'income'           => ['required', 'numeric', 'min:1000'],
            'age'              => ['required', 'integer', 'min:18', 'max:70'],
            'credit_score'     => ['required', 'integer', 'min:300', 'max:850'],
            'employment_years' => ['required', 'integer', 'min:0', 'max:50'],
            'purpose'          => ['required', 'in:home,car,education,business,personal'],
        ];
    }

    public function messages(): array
    {
        return [
            'loan_amount.min'  => 'Minimum loan amount is PKR 1,000.',
            'age.min'          => 'Applicant must be at least 18 years old.',
            'credit_score.min' => 'Credit score must be between 300 and 850.',
            'credit_score.max' => 'Credit score must be between 300 and 850.',
            'purpose.in'       => 'Select a valid loan purpose.',
        ];
    }
}
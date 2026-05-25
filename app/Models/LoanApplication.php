<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'loan_amount', 'income', 'age',
        'credit_score', 'employment_years', 'purpose',
        'risk_score', 'risk_label', 'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function riskBadge(): string
    {
        return match($this->risk_label) {
            'high'  => 'danger',
            'low'   => 'success',
            default => 'secondary',
        };
    }

    public function statusBadge(): string
    {
        return match($this->status) {
            'approved' => 'success',
            'rejected' => 'danger',
            default    => 'warning',
        };
    }
}
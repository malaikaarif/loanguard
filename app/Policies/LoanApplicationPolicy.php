<?php

namespace App\Policies;

use App\Models\LoanApplication;
use App\Models\User;

class LoanApplicationPolicy
{
    public function before(User $user, string $ability): bool|null
    {
        if ($user->role === 'admin') return true;
        return null;
    }

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, LoanApplication $application): bool
    {
        return $user->id === $application->user_id;
    }

    public function create(User $user): bool
    {
        return $user->role === 'applicant';
    }

    public function delete(User $user, LoanApplication $application): bool
    {
        return $user->id === $application->user_id
            && $application->status === 'pending';
    }

    public function updateStatus(User $user): bool
    {
        return $user->role === 'admin';
    }
}
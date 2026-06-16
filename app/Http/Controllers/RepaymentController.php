<?php

namespace App\Http\Controllers;

use App\Models\LoanApplication;
use App\Models\LoanHistory;
use App\Models\Repayment;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;

class RepaymentController extends Controller
{
    use AuthorizesRequests;
    // Generate installments when loan is approved
    public static function generateInstallments(LoanApplication $loan)
    {
        $months = 12; // 12 monthly installments
      $interest = DB::table('settings')->where('key', 'interest_rate')->value('value') / 100;
        $total = $loan->loan_amount * (1 + $interest);
        $monthly = $total / $months;

        for ($i = 1; $i <= $months; $i++) {
            Repayment::create([
                'loan_application_id' => $loan->id,
                'installment_number'  => $i,
                'amount'              => round($monthly, 2),
                'due_date'            => Carbon::now()->addMonths($i),
                'status'              => 'pending',
            ]);
        }
    }

    // Applicant: view repayment schedule
    public function show(LoanApplication $loanApplication)
    {
        $this->authorize('view', $loanApplication);
        $repayments = $loanApplication->repayments()->orderBy('installment_number')->get();
        $history    = $loanApplication->loanHistories()->latest()->get();
        return view('repayments.show', compact('loanApplication', 'repayments', 'history'));
    }

    // Applicant: pay installment
    public function pay(LoanApplication $loanApplication, Repayment $repayment)
    {
        $this->authorize('view', $loanApplication);

        $repayment->update([
            'status'    => 'paid',
            'paid_date' => Carbon::now(),
        ]);

        // Check if all paid → close loan
        $pending = $loanApplication->repayments()->where('status', '!=', 'paid')->count();
        if ($pending === 0) {
            $loanApplication->update(['status' => 'closed']);
            LoanHistory::create([
                'loan_application_id' => $loanApplication->id,
                'old_status'          => 'approved',
                'new_status'          => 'closed',
                'note'                => 'All installments paid. Loan closed.',
                'changed_by'          => auth()->id(),
            ]);
        }

        return redirect()->back()->with('success', 'Installment #' . $repayment->installment_number . ' paid successfully!');
    }

    // Admin: view all repayments
    public function index()
    {
        $loans = LoanApplication::with(['user', 'repayments'])
            ->where('status', 'approved')
            ->latest()
            ->get();
        return view('repayments.index', compact('loans'));
    }
}
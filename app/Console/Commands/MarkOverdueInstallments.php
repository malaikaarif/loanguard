<?php

namespace App\Console\Commands;

use App\Models\Repayment;
use Carbon\Carbon;
use Illuminate\Console\Command;

class MarkOverdueInstallments extends Command
{
    protected $signature = 'repayments:mark-overdue';
    protected $description = 'Mark overdue installments automatically';

    public function handle()
    {
        $count = Repayment::where('status', 'pending')
            ->where('due_date', '<', Carbon::today())
            ->update(['status' => 'overdue']);

        $this->info("Marked {$count} installments as overdue.");
    }
}
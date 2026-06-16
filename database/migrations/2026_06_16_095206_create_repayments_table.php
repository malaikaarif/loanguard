<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
{
    Schema::create('repayments', function (Blueprint $table) {
        $table->id();
        $table->foreignId('loan_application_id')->constrained()->onDelete('cascade');
        $table->integer('installment_number');
        $table->decimal('amount', 12, 2);
        $table->date('due_date');
        $table->date('paid_date')->nullable();
        $table->enum('status', ['pending', 'paid', 'overdue'])->default('pending');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repayments');
    }
};

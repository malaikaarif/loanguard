<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loan_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('loan_amount', 12, 2);
            $table->decimal('income', 12, 2);
            $table->integer('age');
            $table->integer('credit_score');
            $table->integer('employment_years');
            $table->string('purpose');
            $table->decimal('risk_score', 5, 4)->nullable();
            $table->string('risk_label')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loan_applications');
    }
};
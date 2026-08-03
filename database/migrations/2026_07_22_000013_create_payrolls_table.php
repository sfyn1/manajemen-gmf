<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coach_id')->constrained('coaches')->cascadeOnDelete();
            $table->integer('year');
            $table->integer('month')->comment('1-12');
            $table->integer('total_sessions')->default(0);
            $table->decimal('rate_per_session', 10, 2)->comment('Rate saat payroll digenerate');
            $table->decimal('total_amount', 10, 2);
            $table->enum('status', ['pending', 'paid'])->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->foreignId('paid_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['coach_id', 'year', 'month'], 'unique_coach_payroll_period');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};

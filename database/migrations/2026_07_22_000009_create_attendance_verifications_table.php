<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coach_id')->constrained('coaches')->cascadeOnDelete();
            $table->foreignId('class_schedule_id')->constrained('class_schedules');
            $table->date('session_date')->comment('Tanggal sesi mengajar');
            $table->enum('status', ['pending', 'approved', 'rejected', 'auto_failed'])->default('pending');
            $table->string('photo_path')->nullable()->comment('Foto verifikasi kehadiran coach');
            $table->timestamp('submitted_at')->nullable()->comment('Waktu coach submit foto');
            $table->timestamp('deadline_at')->nullable()->comment('Deadline submit = jam selesai kelas + 1 jam');
            $table->timestamp('reviewed_at')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('rejection_reason')->nullable();
            $table->boolean('commission_paid')->default(false);
            $table->decimal('commission_amount', 10, 2)->nullable()->comment('Salinan rate coach saat sesi ini disetujui');
            $table->timestamps();

            $table->unique(['coach_id', 'class_schedule_id', 'session_date'], 'unique_session_verification');
            $table->index(['status', 'submitted_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_verifications');
    }
};

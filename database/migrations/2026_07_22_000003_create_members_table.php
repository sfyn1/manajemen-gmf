<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('membership_package_id')->constrained('membership_packages');
            $table->string('full_name');
            $table->string('nik', 20)->unique()->comment('Nomor Induk Kependudukan — primary unique identifier');
            $table->enum('gender', ['male', 'female']);
            $table->date('birth_date');
            $table->text('address');
            $table->string('phone', 20);
            $table->string('email')->unique();
            $table->enum('status', ['pending_verification', 'active', 'rejected', 'expired'])->default('pending_verification');
            $table->string('rejection_reason')->nullable();
            $table->date('membership_start_date')->nullable();
            $table->date('membership_end_date')->nullable();
            $table->string('qr_token', 64)->unique()->nullable()->comment('Token unik untuk generate QR code presensi gym');
            $table->timestamp('qr_expires_at')->nullable()->comment('Null = ikut membership_end_date; isi untuk daily pass');
            $table->timestamps();

            $table->index(['status', 'membership_end_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('class_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained('members')->cascadeOnDelete();
            $table->foreignId('class_schedule_id')->constrained('class_schedules');
            $table->date('booking_date')->comment('Tanggal spesifik kelas yang dibooking');
            $table->enum('status', ['booked', 'attended', 'no_show', 'cancelled'])->default('booked');
            $table->boolean('payment_confirmed')->default(false)->comment('Dibayar di tempat, dikonfirmasi admin');
            $table->decimal('payment_amount', 10, 2)->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->string('cancellation_reason')->nullable();
            $table->timestamps();

            $table->unique(['member_id', 'class_schedule_id', 'booking_date'], 'unique_booking');
            $table->index(['booking_date', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('class_bookings');
    }
};

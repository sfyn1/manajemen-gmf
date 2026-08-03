<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('class_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_type_id')->constrained('class_types');
            $table->foreignId('coach_id')->constrained('coaches');
            $table->string('day_of_week')->comment('monday, tuesday, ..., sunday');
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('max_capacity')->default(20)->comment('Kapasitas maksimal peserta per kelas');
            $table->decimal('session_fee', 10, 2)->default(0)->comment('Biaya per sesi untuk member (bayar di tempat)');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('class_schedules');
    }
};

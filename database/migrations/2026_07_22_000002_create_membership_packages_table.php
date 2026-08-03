<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('membership_packages', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Reguler, Harian, Pelajar
            $table->enum('type', ['monthly_regular', 'daily', 'monthly_student']);
            $table->decimal('price', 10, 2);
            $table->integer('duration_days')->comment('Durasi dalam hari: 30 untuk bulanan, 1 untuk harian');
            $table->text('description')->nullable();
            $table->string('required_document')->comment('KTP atau KTM/kartu_pelajar');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('membership_packages');
    }
};

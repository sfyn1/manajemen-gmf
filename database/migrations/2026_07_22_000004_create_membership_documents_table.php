<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('membership_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained('members')->cascadeOnDelete();
            $table->string('identity_document_path')->comment('Path file KTP atau KTM/kartu pelajar');
            $table->string('identity_document_type')->comment('ktp / ktm');
            $table->string('payment_proof_path')->comment('Path foto bukti transfer QRIS');
            $table->decimal('payment_amount', 10, 2)->nullable()->comment('Nominal yang tertera di bukti transfer');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('membership_documents');
    }
};

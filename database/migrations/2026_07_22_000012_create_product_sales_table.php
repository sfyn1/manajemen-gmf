<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products');
            $table->foreignId('sold_by')->constrained('users')->comment('Admin yang mencatat penjualan');
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2)->comment('Harga saat transaksi (snapshot)');
            $table->decimal('total_price', 10, 2);
            $table->enum('payment_method', ['cash', 'qris'])->default('cash');
            $table->string('buyer_name')->nullable()->comment('Nama pembeli, opsional');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_sales');
    }
};

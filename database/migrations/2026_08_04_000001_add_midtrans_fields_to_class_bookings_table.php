<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('class_bookings', function (Blueprint $table) {
            $table->string('order_id')->nullable()->after('status');
            $table->string('snap_token')->nullable()->after('order_id');
            $table->string('payment_type')->default('midtrans_snap')->after('snap_token');
            $table->string('payment_status')->default('pending')->after('payment_type')->comment('pending, settlement, cancelled, refunded');
        });
    }

    public function down(): void
    {
        Schema::table('class_bookings', function (Blueprint $table) {
            $table->dropColumn(['order_id', 'snap_token', 'payment_type', 'payment_status']);
        });
    }
};

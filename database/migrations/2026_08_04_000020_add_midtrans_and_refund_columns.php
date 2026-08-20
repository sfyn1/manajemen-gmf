<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->string('order_id')->nullable()->unique()->after('status');
            $table->string('snap_token')->nullable()->after('order_id');
            $table->string('payment_type')->nullable()->after('snap_token');
            $table->string('payment_status')->default('pending')->after('payment_type');
            $table->decimal('refund_amount', 12, 2)->nullable()->after('payment_status');
            $table->text('refund_reason')->nullable()->after('refund_amount');
            $table->timestamp('refunded_at')->nullable()->after('refund_reason');
            $table->string('refund_status')->nullable()->after('refunded_at');
        });

        Schema::table('membership_renewals', function (Blueprint $table) {
            $table->string('order_id')->nullable()->unique()->after('status');
            $table->string('snap_token')->nullable()->after('order_id');
            $table->string('payment_type')->nullable()->after('snap_token');
            $table->string('payment_status')->default('pending')->after('payment_type');
            $table->decimal('refund_amount', 12, 2)->nullable()->after('payment_status');
            $table->text('refund_reason')->nullable()->after('refund_amount');
            $table->timestamp('refunded_at')->nullable()->after('refund_reason');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropColumn([
                'order_id',
                'snap_token',
                'payment_type',
                'payment_status',
                'refund_amount',
                'refund_reason',
                'refunded_at',
                'refund_status',
            ]);
        });

        Schema::table('membership_renewals', function (Blueprint $table) {
            $table->dropColumn([
                'order_id',
                'snap_token',
                'payment_type',
                'payment_status',
                'refund_amount',
                'refund_reason',
                'refunded_at',
            ]);
        });
    }
};

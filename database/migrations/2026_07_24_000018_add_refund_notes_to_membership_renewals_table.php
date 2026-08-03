<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('membership_renewals', function (Blueprint $table) {
            $table->text('refund_notes')->nullable()->after('rejection_reason')->comment('Catatan / instruksi pengembalian dana refund manual');
        });
    }

    public function down(): void
    {
        Schema::table('membership_renewals', function (Blueprint $table) {
            $table->dropColumn('refund_notes');
        });
    }
};

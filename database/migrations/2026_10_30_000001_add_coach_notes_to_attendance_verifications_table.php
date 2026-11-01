<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('attendance_verifications', 'coach_notes')) {
            Schema::table('attendance_verifications', function (Blueprint $table) {
                $table->text('coach_notes')->nullable()->after('rejection_reason');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('attendance_verifications', 'coach_notes')) {
            Schema::table('attendance_verifications', function (Blueprint $table) {
                $table->dropColumn('coach_notes');
            });
        }
    }
};

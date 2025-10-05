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
        Schema::table('donors', function (Blueprint $table) {
            if (Schema::hasColumn('donors', 'kta_number')) {
                $table->dropUnique('donors_kta_number_unique');
                $table->string('kta_number')->nullable()->change();
            }

            if (! Schema::hasColumn('donors', 'kta_issued_at')) {
                $table->timestamp('kta_issued_at')->nullable()->after('kta_number');
            }

            if (! Schema::hasColumn('donors', 'qr_code_path')) {
                $table->string('qr_code_path')->nullable()->after('kta_issued_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('donors', function (Blueprint $table) {
            if (Schema::hasColumn('donors', 'kta_issued_at')) {
                $table->dropColumn('kta_issued_at');
            }

            if (Schema::hasColumn('donors', 'qr_code_path')) {
                $table->dropColumn('qr_code_path');
            }

            if (Schema::hasColumn('donors', 'kta_number')) {
                $table->string('kta_number')->nullable(false)->change();
                $table->unique('kta_number');
            }
        });
    }
};

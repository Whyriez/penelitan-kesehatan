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
        Schema::table('arsip_izin_kesehatan', function (Blueprint $table) {
            $table->json('file_revisi')->nullable()->after('catatan_revisi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('arsip_izin_kesehatan', function (Blueprint $table) {
            $table->dropColumn('file_revisi');
        });
    }
};

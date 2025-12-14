<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('arsip_izin_kesehatan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // Relasi ke tabel jenis_izins (Dropdown)
            $table->foreignId('jenis_izin_id')->constrained('jenis_izins')->onDelete('cascade');

            $table->string('nama'); // Judul/Nama Pengajuan
            $table->text('deskripsi')->nullable();

            // Data Spesifik
            $table->string('tempat_praktek'); // Lokasi Penelitian / Tempat Praktik
            $table->string('nomor_surat'); // Tanggal User Mengajukan
            $table->date('tgl_surat'); // Tanggal User Mengajukan

            // Data Admin (Harus Nullable saat pengajuan awal)
            $table->string('nomor_izin')->nullable();
            $table->date('tgl_terbit')->nullable();

            $table->json('file'); // Dokumen
            $table->enum('status', ['pending', 'valid', 'draft', 'revisi'])->default('pending');
            $table->text('catatan_revisi')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arsip_penelitian_kesehatan');
    }
};

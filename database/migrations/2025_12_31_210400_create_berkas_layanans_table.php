<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Membuat tabel 'berkas_layanans' (TABEL UTAMA/UNIFIED).
     * Tabel ini menggantikan tabel-tabel terpisah sebelumnya (veras, mskis, dll).
     * Relasi:
     * - divisi_id -> table divisis (Cascade Delete)
     * - user_id -> table users (Pengirim)
     * - staff_id -> table users (Pemroses)
     */
    public function up(): void
    {
        Schema::create('berkas_layanans', function (Blueprint $table) {
            $table->id();
            $table->string('no_berkas')->unique(); // Nomor Surat Unik
            $table->foreignId('divisi_id')->constrained('divisis')->onDelete('cascade'); // Link ke Divisi
            $table->string('id_satker');
            $table->string('jenis_layanan');
            $table->text('keterangan')->nullable();
            $table->string('file_path')->nullable(); // Path file upload
            $table->string('original_filename')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete(); // User Pengirim
            $table->foreignId('staff_id')->nullable()->constrained('users')->nullOnDelete(); // Staff Pemroses
            $table->enum('status', ['baru', 'diproses', 'selesai', 'ditolak'])->default('baru');
            $table->text('feedback')->nullable();
            $table->string('feedback_file')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('berkas_layanans');
    }
};

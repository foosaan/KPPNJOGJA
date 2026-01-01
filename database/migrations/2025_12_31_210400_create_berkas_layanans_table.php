<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('berkas_layanans', function (Blueprint $table) {
            $table->id();
            $table->string('no_berkas')->unique();
            $table->foreignId('divisi_id')->constrained('divisis')->onDelete('cascade');
            $table->string('id_satker');
            $table->string('jenis_layanan');
            $table->text('keterangan')->nullable();
            $table->string('file_path')->nullable();
            $table->string('original_filename')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('staff_id')->nullable()->constrained('users')->nullOnDelete();
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

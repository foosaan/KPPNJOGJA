<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Drop legacy tables that are no longer needed.
     * Data has been migrated to the unified 'berkas_layanans' table.
     */
    public function up(): void
    {
        Schema::dropIfExists('veras');
        Schema::dropIfExists('mskis');
        Schema::dropIfExists('pds');
        Schema::dropIfExists('banks');
        Schema::dropIfExists('umums');
    }

    /**
     * Reverse the migrations.
     * Note: This will recreate empty tables without data.
     */
    public function down(): void
    {
        // Recreate veras table
        Schema::create('veras', function (Blueprint $table) {
            $table->id();
            $table->string('no_berkas')->unique();
            $table->string('id_satker');
            $table->text('keterangan')->nullable();
            $table->string('file_path')->nullable();
            $table->string('original_filename')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('staff_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', ['baru', 'diproses', 'selesai', 'ditolak'])->default('baru');
            $table->text('alasan_penolakan')->nullable();
            $table->text('feedback')->nullable();
            $table->string('feedback_file')->nullable();
            $table->timestamps();
        });

        // Recreate mskis table
        Schema::create('mskis', function (Blueprint $table) {
            $table->id();
            $table->string('no_berkas')->unique();
            $table->string('id_satker');
            $table->text('keterangan')->nullable();
            $table->string('file_path')->nullable();
            $table->string('original_filename')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('staff_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', ['baru', 'diproses', 'selesai', 'ditolak'])->default('baru');
            $table->text('alasan_penolakan')->nullable();
            $table->text('feedback')->nullable();
            $table->string('feedback_file')->nullable();
            $table->timestamps();
        });

        // Recreate pds table
        Schema::create('pds', function (Blueprint $table) {
            $table->id();
            $table->string('no_berkas')->unique();
            $table->string('id_satker');
            $table->text('keterangan')->nullable();
            $table->string('file_path')->nullable();
            $table->string('original_filename')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('staff_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', ['baru', 'diproses', 'selesai', 'ditolak'])->default('baru');
            $table->text('alasan_penolakan')->nullable();
            $table->text('feedback')->nullable();
            $table->string('feedback_file')->nullable();
            $table->timestamps();
        });

        // Recreate banks table
        Schema::create('banks', function (Blueprint $table) {
            $table->id();
            $table->string('no_berkas')->unique();
            $table->string('id_satker');
            $table->text('keterangan')->nullable();
            $table->string('file_path')->nullable();
            $table->string('original_filename')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('staff_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', ['baru', 'diproses', 'selesai', 'ditolak'])->default('baru');
            $table->text('alasan_penolakan')->nullable();
            $table->text('feedback')->nullable();
            $table->string('feedback_file')->nullable();
            $table->timestamps();
        });

        // Recreate umums table
        Schema::create('umums', function (Blueprint $table) {
            $table->id();
            $table->string('no_berkas')->unique();
            $table->string('id_satker');
            $table->text('keterangan')->nullable();
            $table->string('file_path')->nullable();
            $table->string('original_filename')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('staff_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', ['baru', 'diproses', 'selesai', 'ditolak'])->default('baru');
            $table->text('alasan_penolakan')->nullable();
            $table->text('feedback')->nullable();
            $table->string('feedback_file')->nullable();
            $table->timestamps();
        });
    }
};

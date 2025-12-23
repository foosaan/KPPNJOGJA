<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVerasTable extends Migration
{
    public function up(): void
    {
        Schema::create('veras', function (Blueprint $table) {
            $table->id();
            $table->string('id_satker'); // ID satker bisa berupa NIP atau kode
            $table->string('no_berkas'); // ← Tambahkan baris ini
            $table->string('jenis_layanan'); // Jenis layanan dari dropdown
            $table->text('keterangan')->nullable(); // Bisa kosong
            $table->string('file_path'); // Menyimpan path file upload
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('veras');
    }
}

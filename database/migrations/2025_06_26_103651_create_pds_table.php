<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePdsTable extends Migration
{
    public function up(): void
    {
        Schema::create('pds', function (Blueprint $table) {
            $table->id();
            $table->string('id_satker');
            $table->string('no_berkas');
            $table->string('jenis_layanan');
            $table->text('keterangan');
            $table->string('file_path');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pds');
    }
}

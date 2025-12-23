<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('layanans', function (Blueprint $table) {
            $table->enum('layanan_type', ['Vera', 'PD', 'MSKI', 'Bank'])->after('id');
            $table->string('jenis_layanan')->unique()->after('layanan_type');
            $table->text('deskripsi')->nullable()->after('jenis_layanan');
            $table->boolean('is_active')->default(true)->after('deskripsi');

            $table->index('layanan_type');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('layanans', function (Blueprint $table) {
            $table->dropIndex(['layanan_type']);
            $table->dropIndex(['is_active']);

            $table->dropColumn([
                'layanan_type',
                'jenis_layanan',
                'deskripsi',
                'is_active',
            ]);
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('veras', function (Blueprint $table) {
        $table->text('alasan_penolakan')->nullable()->after('status');
    });

    Schema::table('pds', function (Blueprint $table) {
        $table->text('alasan_penolakan')->nullable()->after('status');
    });

    Schema::table('mskis', function (Blueprint $table) {
        $table->text('alasan_penolakan')->nullable()->after('status');
    });

    Schema::table('banks', function (Blueprint $table) {
        $table->text('alasan_penolakan')->nullable()->after('status');
    });
}

public function down()
{
    Schema::table('veras', function (Blueprint $table) {
        $table->dropColumn('alasan_penolakan');
    });

    Schema::table('pds', function (Blueprint $table) {
        $table->dropColumn('alasan_penolakan');
    });

    Schema::table('mskis', function (Blueprint $table) {
        $table->dropColumn('alasan_penolakan');
    });

    Schema::table('banks', function (Blueprint $table) {
        $table->dropColumn('alasan_penolakan');
    });
}

};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('banks', function (Blueprint $table) {
            $table->text('feedback')->nullable();
            $table->string('feedback_file')->nullable();
        });

        Schema::table('mskis', function (Blueprint $table) {
            $table->text('feedback')->nullable();
            $table->string('feedback_file')->nullable();
        });

        Schema::table('pds', function (Blueprint $table) {
            $table->text('feedback')->nullable();
            $table->string('feedback_file')->nullable();
        });

        Schema::table('veras', function (Blueprint $table) {
            $table->text('feedback')->nullable();
            $table->string('feedback_file')->nullable();
        });

        Schema::table('umums', function (Blueprint $table) {
            $table->text('feedback')->nullable();
            $table->string('feedback_file')->nullable();
        });
    }

    public function down()
    {
        Schema::table('banks', function (Blueprint $table) {
            $table->dropColumn(['feedback','feedback_file']);
        });

        Schema::table('mskis', function (Blueprint $table) {
            $table->dropColumn(['feedback','feedback_file']);
        });

        Schema::table('pds', function (Blueprint $table) {
            $table->dropColumn(['feedback','feedback_file']);
        });

        Schema::table('veras', function (Blueprint $table) {
            $table->dropColumn(['feedback','feedback_file']);
        });

        Schema::table('umums', function (Blueprint $table) {
            $table->dropColumn(['feedback','feedback_file']);
        });
    }
};

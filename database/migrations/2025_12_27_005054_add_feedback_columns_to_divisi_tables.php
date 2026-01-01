<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('banks', function (Blueprint $table) {
            if (!Schema::hasColumn('banks', 'feedback')) {
                $table->text('feedback')->nullable();
            }
            if (!Schema::hasColumn('banks', 'feedback_file')) {
                $table->string('feedback_file')->nullable();
            }
        });

        Schema::table('mskis', function (Blueprint $table) {
            if (!Schema::hasColumn('mskis', 'feedback')) {
                $table->text('feedback')->nullable();
            }
            if (!Schema::hasColumn('mskis', 'feedback_file')) {
                $table->string('feedback_file')->nullable();
            }
        });

        Schema::table('pds', function (Blueprint $table) {
            if (!Schema::hasColumn('pds', 'feedback')) {
                $table->text('feedback')->nullable();
            }
            if (!Schema::hasColumn('pds', 'feedback_file')) {
                $table->string('feedback_file')->nullable();
            }
        });

        Schema::table('veras', function (Blueprint $table) {
            if (!Schema::hasColumn('veras', 'feedback')) {
                $table->text('feedback')->nullable();
            }
            if (!Schema::hasColumn('veras', 'feedback_file')) {
                $table->string('feedback_file')->nullable();
            }
        });

        Schema::table('umums', function (Blueprint $table) {
            if (!Schema::hasColumn('umums', 'feedback')) {
                $table->text('feedback')->nullable();
            }
            if (!Schema::hasColumn('umums', 'feedback_file')) {
                $table->string('feedback_file')->nullable();
            }
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

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
        Schema::table('umums', function (Blueprint $table) {
            if (!Schema::hasColumn('umums', 'status')) {
                $table->string('status')->default('baru')->after('keterangan');
            }
            if (!Schema::hasColumn('umums', 'staff_id')) {
                $table->unsignedBigInteger('staff_id')->nullable()->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('umums', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('staff_id');
        });
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // researcher_status — tracks special cases
            // null = normal active researcher
            // 'irregular' = delayed graduation, year level updated
            // 'dropped_out' = read-only, cannot submit
            $table->string('researcher_status')->nullable()->after('graduated_at');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('researcher_status');
        });
    }
};
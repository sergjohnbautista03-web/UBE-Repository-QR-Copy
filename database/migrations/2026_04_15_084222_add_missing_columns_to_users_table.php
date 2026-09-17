<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            // Approval & admin flags
            $table->boolean('is_approved')->default(false)->after('is_active');
            $table->boolean('is_super_admin')->default(false)->after('is_approved');

            // Activity tracking
            $table->timestamp('last_seen_at')->nullable()->after('is_super_admin');

            // Student / researcher academic info
            $table->unsignedTinyInteger('year_level')->nullable()->after('student_id');
            $table->unsignedTinyInteger('course_duration')->nullable()->after('year_level');
            $table->unsignedSmallInteger('graduation_year')->nullable()->after('course_duration');
            $table->timestamp('graduated_at')->nullable()->after('graduation_year');

            // Researcher status (active / irregular / dropped_out)
            $table->string('researcher_status')->nullable()->after('graduated_at');

            // Role: set 'user' role as approved by default
            // (handled in seeder/factory or via the default above)
        });

        // Users with role='user' are auto-approved
        \DB::table('users')->where('role', 'user')->update(['is_approved' => true]);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'is_approved',
                'is_super_admin',
                'last_seen_at',
                'year_level',
                'course_duration',
                'graduation_year',
                'graduated_at',
                'researcher_status',
            ]);
        });
    }
};
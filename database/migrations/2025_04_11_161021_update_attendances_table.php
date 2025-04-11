<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            // Add new columns
            $table->foreignId('profile_id')->after('id')->constrained()->onDelete('cascade');
            $table->timestamp('time_in')->nullable()->change();
            $table->timestamp('time_out')->nullable()->change();
            $table->enum('status', ['present', 'late', 'excused', 'unexcused'])->nullable()->change();
            $table->string('excuse_reason')->nullable()->after('Status');
            $table->json('meta_data')->nullable()->after('excuse_reason');
            $table->string('device_id')->nullable()->after('meta_data');
            $table->string('location')->nullable()->after('device_id');
            $table->json('schedule_data')->nullable()->after('location');
            $table->softDeletes();

            // Remove redundant columns since they're in the profile
            $table->dropColumn(['FullName', 'YearLevel', 'Course']);
        });
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropForeign(['profile_id']);
            $table->dropColumn([
                'profile_id',
                'excuse_reason',
                'meta_data',
                'device_id',
                'location',
                'schedule_data',
                'deleted_at'
            ]);
            $table->string('time_in')->nullable()->change();
            $table->string('time_out')->nullable()->change();
            $table->string('Status')->nullable()->change();
            
            // Restore removed columns
            $table->string('FullName')->nullable();
            $table->string('YearLevel')->nullable();
            $table->string('Course')->nullable();
        });
    }
};

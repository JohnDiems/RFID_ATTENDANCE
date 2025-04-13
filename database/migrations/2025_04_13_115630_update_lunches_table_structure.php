<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First check if lunch_in and lunch_out columns exist
        if (Schema::hasColumn('lunches', 'lunch_in')) {
            Schema::table('lunches', function (Blueprint $table) {
                // Rename lunch_in to time_in if it exists
                $table->renameColumn('lunch_in', 'time_in');
            });
        }
        
        if (Schema::hasColumn('lunches', 'lunch_out')) {
            Schema::table('lunches', function (Blueprint $table) {
                // Rename lunch_out to time_out if it exists
                $table->renameColumn('lunch_out', 'time_out');
            });
        }
        
        // Add any missing columns
        Schema::table('lunches', function (Blueprint $table) {
            if (!Schema::hasColumn('lunches', 'profile_id')) {
                $table->foreignId('profile_id')->nullable();
            }
            
            if (!Schema::hasColumn('lunches', 'status')) {
                $table->string('status')->nullable();
            }
            
            if (!Schema::hasColumn('lunches', 'device_id')) {
                $table->string('device_id')->nullable();
            }
            
            if (!Schema::hasColumn('lunches', 'location')) {
                $table->string('location')->nullable();
            }
            
            if (!Schema::hasColumn('lunches', 'meta_data')) {
                $table->json('meta_data')->nullable();
            }
            
            if (!Schema::hasColumn('lunches', 'menu_data')) {
                $table->json('menu_data')->nullable();
            }
            
            // Make sure timestamps and soft deletes exist
            if (!Schema::hasColumn('lunches', 'created_at')) {
                $table->timestamps();
            }
            
            if (!Schema::hasColumn('lunches', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This is a complex migration, so we won't provide a rollback
    }
};

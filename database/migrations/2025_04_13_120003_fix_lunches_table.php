<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if the lunches table exists
        if (Schema::hasTable('lunches')) {
            // Instead of dropping, we'll modify the existing table
            Schema::table('lunches', function (Blueprint $table) {
                // Check and add columns if they don't exist
                if (!Schema::hasColumn('lunches', 'time_in') && Schema::hasColumn('lunches', 'lunch_in')) {
                    // Rename lunch_in to time_in if it exists
                    DB::statement('ALTER TABLE lunches CHANGE lunch_in time_in TIMESTAMP NULL DEFAULT NULL');
                }
                
                if (!Schema::hasColumn('lunches', 'time_out') && Schema::hasColumn('lunches', 'lunch_out')) {
                    // Rename lunch_out to time_out if it exists
                    DB::statement('ALTER TABLE lunches CHANGE lunch_out time_out TIMESTAMP NULL DEFAULT NULL');
                }
                
                // Add any missing columns
                if (!Schema::hasColumn('lunches', 'profile_id')) {
                    $table->foreignId('profile_id')->nullable()->constrained()->onDelete('cascade');
                }
                
                if (!Schema::hasColumn('lunches', 'card_id')) {
                    $table->foreignId('card_id')->nullable()->constrained()->onDelete('cascade');
                }
                
                if (!Schema::hasColumn('lunches', 'status')) {
                    $table->string('status')->nullable()->default('complete');
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
                
                // Add timestamps and soft deletes if they don't exist
                if (!Schema::hasColumn('lunches', 'created_at')) {
                    $table->timestamps();
                }
                
                if (!Schema::hasColumn('lunches', 'deleted_at')) {
                    $table->softDeletes();
                }
            });
        } else {
            // Create the table if it doesn't exist
            Schema::create('lunches', function (Blueprint $table) {
                $table->id();
                $table->foreignId('profile_id')->nullable()->constrained()->onDelete('cascade');
                $table->foreignId('card_id')->nullable()->constrained()->onDelete('cascade');
                $table->string('StudentRFID')->nullable();
                $table->date('date');
                $table->timestamp('time_in')->nullable();
                $table->timestamp('time_out')->nullable();
                $table->string('status')->default('complete');
                $table->string('device_id')->nullable();
                $table->string('location')->nullable();
                $table->json('meta_data')->nullable();
                $table->json('menu_data')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to drop the table in the down method
    }
};

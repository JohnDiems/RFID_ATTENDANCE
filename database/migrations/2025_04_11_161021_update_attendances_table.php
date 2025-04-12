<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            // This migration is no longer needed as all changes are in the initial migration
        });
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            // This migration is no longer needed as all changes are in the initial migration
        });
    }
};

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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->string('FullName');
            $table->string('photo')->nullable();
            $table->string('StudentRFID')->unique();
            $table->string('Parent')->nullable();
            $table->string('EmergencyAddress')->nullable();
            $table->string('EmergencyNumber')->nullable();
            $table->string('YearLevel')->nullable();
            $table->string('Course')->nullable();
            $table->string('Gender')->nullable();
            $table->string('CompleteAddress')->nullable();
            $table->string('ContactNumber')->nullable();
            $table->string('EmailAddress')->nullable();
            $table->boolean('Status')->default(true);
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};

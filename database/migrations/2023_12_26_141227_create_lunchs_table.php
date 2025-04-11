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
        Schema::create('lunchs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('photo')->nullable();
            $table->string('StudentRFID');
            $table->string('FullName')->nullable();
            $table->string('YearLevel')->nullable();
            $table->string('Course')->nullable();
            $table->string('lunch_in')->nullable();
            $table->string('lunch_out')->nullable();
            $table->string('Status')->nullable();
            $table->date('date')->nullable();
            $table->timestamps();

            // Foreign key column to reference the cards table
            $table->unsignedBigInteger('card_id');
            $table->foreign('card_id')->references('id')->on('cards')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lunchs');
    }
};

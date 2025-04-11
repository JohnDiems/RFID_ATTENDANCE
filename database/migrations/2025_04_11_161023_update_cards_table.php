<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cards', function (Blueprint $table) {
            // Add new columns
            $table->string('card_number')->unique()->after('id');
            $table->enum('status', ['active', 'inactive', 'lost', 'expired'])->default('active')->after('card_number');
            $table->timestamp('issued_at')->nullable()->after('status');
            $table->timestamp('expires_at')->nullable()->after('issued_at');
            $table->json('access_permissions')->nullable()->after('expires_at');
            $table->json('meta_data')->nullable()->after('access_permissions');
            $table->timestamps();
            $table->softDeletes();

            // Make lunch times nullable
            $table->time('lunch_in')->nullable()->change();
            $table->time('lunch_out')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('cards', function (Blueprint $table) {
            $table->dropColumn([
                'card_number',
                'status',
                'issued_at',
                'expires_at',
                'access_permissions',
                'meta_data',
                'created_at',
                'updated_at',
                'deleted_at'
            ]);
            $table->time('lunch_in')->nullable(false)->change();
            $table->time('lunch_out')->nullable(false)->change();
        });
    }
};

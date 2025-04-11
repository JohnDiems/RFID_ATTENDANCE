<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            // Add new columns
            $table->date('birth_date')->nullable()->after('Gender');
            $table->enum('blood_type', ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'])->nullable()->after('birth_date');
            $table->json('medical_conditions')->nullable()->after('blood_type');
            $table->json('emergency_contacts')->nullable()->after('EmergencyNumber');
            $table->string('student_id')->unique()->nullable()->after('StudentRFID');
            $table->string('section')->nullable()->after('YearLevel');
            $table->enum('enrollment_status', ['enrolled', 'graduated', 'transferred', 'dropped'])->default('enrolled')->after('Status');
            $table->softDeletes();

            // Modify existing columns
            $table->string('photo')->nullable()->default('default.jpg')->change();
            $table->enum('Gender', ['male', 'female', 'other'])->nullable()->change();
            $table->string('YearLevel')->nullable()->comment('1st Year, 2nd Year, etc.')->change();
            $table->string('Course')->nullable()->comment('BS Computer Science, BS Information Technology, etc.')->change();
        });
    }

    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn([
                'birth_date',
                'blood_type',
                'medical_conditions',
                'emergency_contacts',
                'student_id',
                'section',
                'enrollment_status',
                'deleted_at'
            ]);
            $table->string('Gender')->nullable()->change();
            $table->string('photo')->nullable()->change();
        });
    }
};

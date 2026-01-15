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
        Schema::table('assessments', function (Blueprint $table) {
            // Add patient details columns (duplicate of patient_name, patient_age, etc. for easier access)
            $table->string('full_name')->nullable()->after('patient_occupation');
            $table->integer('age')->nullable()->after('full_name');
            $table->string('gender')->nullable()->after('age');
            $table->string('occupation')->nullable()->after('gender');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assessments', function (Blueprint $table) {
            $table->dropColumn(['full_name', 'age', 'gender', 'occupation']);
        });
    }
};

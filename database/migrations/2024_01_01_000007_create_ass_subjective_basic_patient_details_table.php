<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ass_subjective_basic_patient_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('assessment_id');
            $table->foreign('assessment_id', 'fk_sub_bpd_assessment')->references('id')->on('assessments')->onDelete('cascade');
            $table->string('full_name', 255)->nullable();
            $table->integer('age')->nullable();
            $table->enum('gender', ['Male', 'Female', 'Other'])->nullable();
            $table->decimal('height', 5, 2)->nullable();
            $table->decimal('weight', 5, 2)->nullable();
            $table->enum('dominance', ['Right', 'Left'])->nullable();
            $table->string('occupation', 255)->nullable();
            $table->enum('activity_level', ['Sedentary', 'Light active', 'Moderate', 'Very active', 'Athlete'])->nullable();
            $table->text('additional_info')->nullable();
            $table->decimal('completion_percentage', 5, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ass_subjective_basic_patient_details');
    }
};


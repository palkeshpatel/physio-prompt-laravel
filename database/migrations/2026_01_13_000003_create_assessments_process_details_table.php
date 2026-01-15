<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assessments_process_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessments_pocess_id')->constrained('assessments_process')->onDelete('cascade');
            $table->enum('assessment_table', [
                // Subjective sections (12)
                'subjective_basic_patient_details',
                'subjective_chief_complaint',
                'subjective_pain_characteristics',
                'subjective_history_present_condition',
                'subjective_functional_limitations',
                'subjective_red_flag_screening',
                'subjective_yellow_flags',
                'subjective_medical_history',
                'subjective_lifestyle_social_history',
                'subjective_ice_assessment',
                'subjective_region_specific',
                'subjective_outcome_measures',
                // Objective sections (10)
                'objective_observations_general_examination',
                'objective_palpation',
                'objective_range_of_motion',
                'objective_muscle_strength',
                'objective_neurological_examination',
                'objective_special_tests',
                'objective_functional_assessment',
                'objective_joint_mobility',
                'objective_outcome_measures',
                'objective_red_flags'
            ]);
            $table->timestamps();

            // Ensure one process can only have one detail per table
            $table->unique(['assessments_pocess_id', 'assessment_table'], 'apd_process_table_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assessments_process_details');
    }
};
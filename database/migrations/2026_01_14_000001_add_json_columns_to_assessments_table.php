<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assessments', function (Blueprint $table) {
            // Completion percentage columns
            $table->decimal('completion_percentage', 5, 2)->default(0)->after('status');
            $table->decimal('subjective_completion_percentage', 5, 2)->default(0)->after('completion_percentage');
            $table->decimal('objective_completion_percentage', 5, 2)->default(0)->after('subjective_completion_percentage');
            
            // Clinical data columns
            $table->text('clinical_impression')->nullable()->after('objective_completion_percentage');
            $table->text('rehab_program')->nullable()->after('clinical_impression');
            $table->string('pdf_path', 255)->nullable()->after('rehab_program');
            $table->timestamp('completed_at')->nullable()->after('pdf_path');
            
            // Patient basic details (for admin listing)
            $table->string('patient_name', 255)->nullable()->after('completed_at');
            $table->integer('patient_age')->nullable()->after('patient_name');
            $table->string('patient_gender', 50)->nullable()->after('patient_age');
            $table->string('patient_occupation', 255)->nullable()->after('patient_gender');
            
            // Subjective Sections (12 JSON columns)
            $table->json('subjective_basic_details')->nullable()->after('patient_occupation');
            $table->json('subjective_chief_complaint')->nullable()->after('subjective_basic_details');
            $table->json('subjective_pain_characteristics')->nullable()->after('subjective_chief_complaint');
            $table->json('subjective_history_present')->nullable()->after('subjective_pain_characteristics');
            $table->json('subjective_functional_limitations')->nullable()->after('subjective_history_present');
            $table->json('subjective_red_flags')->nullable()->after('subjective_functional_limitations');
            $table->json('subjective_yellow_flags')->nullable()->after('subjective_red_flags');
            $table->json('subjective_medical_history')->nullable()->after('subjective_yellow_flags');
            $table->json('subjective_lifestyle')->nullable()->after('subjective_medical_history');
            $table->json('subjective_ice')->nullable()->after('subjective_lifestyle');
            $table->json('subjective_region_specific')->nullable()->after('subjective_ice');
            $table->json('subjective_outcome_measures')->nullable()->after('subjective_region_specific');
            
            // Objective Sections (10 JSON columns)
            $table->json('objective_observation_general')->nullable()->after('subjective_outcome_measures');
            $table->json('objective_palpation')->nullable()->after('objective_observation_general');
            $table->json('objective_range_of_motion')->nullable()->after('objective_palpation');
            $table->json('objective_muscle_strength')->nullable()->after('objective_range_of_motion');
            $table->json('objective_neurological_exam')->nullable()->after('objective_muscle_strength');
            $table->json('objective_special_tests')->nullable()->after('objective_neurological_exam');
            $table->json('objective_functional_assessment')->nullable()->after('objective_special_tests');
            $table->json('objective_joint_mobility')->nullable()->after('objective_functional_assessment');
            $table->json('objective_outcome_measures')->nullable()->after('objective_joint_mobility');
            $table->json('objective_red_flags')->nullable()->after('objective_outcome_measures');
            
            // Add indexes for better performance
            $table->index('user_id');
            $table->index('status');
            $table->index('patient_name');
        });
    }

    public function down(): void
    {
        Schema::table('assessments', function (Blueprint $table) {
            // Remove indexes
            $table->dropIndex(['user_id']);
            $table->dropIndex(['status']);
            $table->dropIndex(['patient_name']);
            
            // Remove columns
            $table->dropColumn([
                'completion_percentage',
                'subjective_completion_percentage',
                'objective_completion_percentage',
                'clinical_impression',
                'rehab_program',
                'pdf_path',
                'completed_at',
                'patient_name',
                'patient_age',
                'patient_gender',
                'patient_occupation',
                'subjective_basic_details',
                'subjective_chief_complaint',
                'subjective_pain_characteristics',
                'subjective_history_present',
                'subjective_functional_limitations',
                'subjective_red_flags',
                'subjective_yellow_flags',
                'subjective_medical_history',
                'subjective_lifestyle',
                'subjective_ice',
                'subjective_region_specific',
                'subjective_outcome_measures',
                'objective_observation_general',
                'objective_palpation',
                'objective_range_of_motion',
                'objective_muscle_strength',
                'objective_neurological_exam',
                'objective_special_tests',
                'objective_functional_assessment',
                'objective_joint_mobility',
                'objective_outcome_measures',
                'objective_red_flags',
            ]);
        });
    }
};

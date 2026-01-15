<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Assessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
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
        'full_name',
        'age',
        'gender',
        'occupation',
        // Subjective JSON columns
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
        // Objective JSON columns
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
    ];

    protected $casts = [
        'completion_percentage' => 'decimal:2',
        'subjective_completion_percentage' => 'decimal:2',
        'objective_completion_percentage' => 'decimal:2',
        'completed_at' => 'datetime',
        'patient_age' => 'integer',
        // Subjective JSON columns
        'subjective_basic_details' => 'array',
        'subjective_chief_complaint' => 'array',
        'subjective_pain_characteristics' => 'array',
        'subjective_history_present' => 'array',
        'subjective_functional_limitations' => 'array',
        'subjective_red_flags' => 'array',
        'subjective_yellow_flags' => 'array',
        'subjective_medical_history' => 'array',
        'subjective_lifestyle' => 'array',
        'subjective_ice' => 'array',
        'subjective_region_specific' => 'array',
        'subjective_outcome_measures' => 'array',
        // Objective JSON columns
        'objective_observation_general' => 'array',
        'objective_palpation' => 'array',
        'objective_range_of_motion' => 'array',
        'objective_muscle_strength' => 'array',
        'objective_neurological_exam' => 'array',
        'objective_special_tests' => 'array',
        'objective_functional_assessment' => 'array',
        'objective_joint_mobility' => 'array',
        'objective_outcome_measures' => 'array',
        'objective_red_flags' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get section data from JSON column
     */
    public function getSectionData(string $sectionName): ?array
    {
        $columnName = $this->getColumnNameForSection($sectionName);
        return $this->$columnName;
    }

    /**
     * Update section data in JSON column
     */
    public function updateSectionData(string $sectionName, array $data): void
    {
        $columnName = $this->getColumnNameForSection($sectionName);
        $this->$columnName = $data;
        $this->save();
    }

    /**
     * Map section name to column name
     */
    private function getColumnNameForSection(string $sectionName): string
    {
        $mapping = [
            // Subjective
            'basic_details' => 'subjective_basic_details',
            'chief_complaint' => 'subjective_chief_complaint',
            'pain_characteristics' => 'subjective_pain_characteristics',
            'history_present' => 'subjective_history_present',
            'functional_limitations' => 'subjective_functional_limitations',
            'red_flags' => 'subjective_red_flags',
            'yellow_flags' => 'subjective_yellow_flags',
            'medical_history' => 'subjective_medical_history',
            'lifestyle' => 'subjective_lifestyle',
            'ice' => 'subjective_ice',
            'region_specific' => 'subjective_region_specific',
            'outcome_measures' => 'subjective_outcome_measures',
            // Objective
            'observation_general' => 'objective_observation_general',
            'palpation' => 'objective_palpation',
            'range_of_motion' => 'objective_range_of_motion',
            'muscle_strength' => 'objective_muscle_strength',
            'neurological_exam' => 'objective_neurological_exam',
            'special_tests' => 'objective_special_tests',
            'functional_assessment' => 'objective_functional_assessment',
            'joint_mobility' => 'objective_joint_mobility',
            'outcome_measures' => 'objective_outcome_measures',
        ];

        return $mapping[$sectionName] ?? $sectionName;
    }

    /**
     * Calculate completion percentage from all sections
     * Note: Does not save the model - call save() separately if needed
     */
    public function calculateCompletionPercentage(): void
    {
        $subjectivePercentage = $this->getSubjectivePercentage();
        $objectivePercentage = $this->getObjectivePercentage();
        
        $this->subjective_completion_percentage = $subjectivePercentage;
        $this->objective_completion_percentage = $objectivePercentage;
        $this->completion_percentage = ($subjectivePercentage + $objectivePercentage) / 2;
    }

    /**
     * Get subjective completion percentage
     * Counts sections that have been submitted (form/page completed, not individual fields)
     * A section is considered completed if the JSON column exists and is an array
     */
    public function getSubjectivePercentage(): float
    {
        $sections = [
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
        ];

        $completed = 0;
        foreach ($sections as $section) {
            // If section JSON column exists and is an array, it means the form was submitted
            if ($this->$section !== null && is_array($this->$section)) {
                $completed++;
            }
        }

        return ($completed / 12) * 100;
    }

    /**
     * Get objective completion percentage
     * Counts sections that have been submitted (form/page completed, not individual fields)
     * A section is considered completed if the JSON column exists and is an array
     */
    public function getObjectivePercentage(): float
    {
        $sections = [
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
        ];

        $completed = 0;
        foreach ($sections as $section) {
            // If section JSON column exists and is an array, it means the form was submitted
            if ($this->$section !== null && is_array($this->$section)) {
                $completed++;
            }
        }

        return ($completed / 10) * 100;
    }

    /**
     * Check if subjective is complete
     */
    public function isSubjectiveComplete(): bool
    {
        return $this->subjective_completion_percentage >= 100;
    }

    /**
     * Check if objective is complete
     */
    public function isObjectiveComplete(): bool
    {
        return $this->objective_completion_percentage >= 100;
    }

    /**
     * Check if assessment is fully complete
     */
    public function isComplete(): bool
    {
        return $this->isSubjectiveComplete() && $this->isObjectiveComplete();
    }
}
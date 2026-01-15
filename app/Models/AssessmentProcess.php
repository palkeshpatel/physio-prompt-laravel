<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class AssessmentProcess extends Model
{
    use HasFactory;

    protected $table = 'assessments_process';

    protected $fillable = [
        'assessments_id',
        'assessment_type_id',
        'status',
        'completion_percentage',
        'clinical_impression',
        'rehab_program',
        'pdf_path',
        'completed_at',
    ];

    protected $casts = [
        'completion_percentage' => 'decimal:2',
        'completed_at' => 'datetime',
    ];

    public function assessment(): BelongsTo
    {
        return $this->belongsTo(Assessment::class, 'assessments_id');
    }

    public function assessmentType(): BelongsTo
    {
        return $this->belongsTo(AssessmentType::class);
    }

    public function processDetails(): HasMany
    {
        return $this->hasMany(AssessmentProcessDetail::class, 'assessments_pocess_id');
    }

    // Subjective Assessment Relationships
    public function basicPatientDetails(): HasOne
    {
        return $this->hasOne(AssSubjectiveBasicPatientDetails::class, 'assessments_process_id');
    }

    public function chiefComplaint(): HasOne
    {
        return $this->hasOne(AssSubjectiveChiefComplaint::class, 'assessments_process_id');
    }

    public function painCharacteristics(): HasOne
    {
        return $this->hasOne(AssSubjectivePainCharacteristics::class, 'assessments_process_id');
    }

    public function historyPresentCondition(): HasOne
    {
        return $this->hasOne(AssSubjectiveHistoryPresentCondition::class, 'assessments_process_id');
    }

    public function functionalLimitations(): HasOne
    {
        return $this->hasOne(AssSubjectiveFunctionalLimitations::class, 'assessments_process_id');
    }

    public function redFlagScreening(): HasOne
    {
        return $this->hasOne(AssSubjectiveRedFlagScreening::class, 'assessments_process_id');
    }

    public function yellowFlags(): HasOne
    {
        return $this->hasOne(AssSubjectiveYellowFlags::class, 'assessments_process_id');
    }

    public function medicalHistory(): HasOne
    {
        return $this->hasOne(AssSubjectiveMedicalHistory::class, 'assessments_process_id');
    }

    public function lifestyleSocialHistory(): HasOne
    {
        return $this->hasOne(AssSubjectiveLifestyleSocialHistory::class, 'assessments_process_id');
    }

    public function iceAssessment(): HasOne
    {
        return $this->hasOne(AssSubjectiveIceAssessment::class, 'assessments_process_id');
    }

    public function regionSpecific(): HasOne
    {
        return $this->hasOne(AssSubjectiveRegionSpecific::class, 'assessments_process_id');
    }

    public function subjectiveOutcomeMeasures(): HasOne
    {
        return $this->hasOne(AssSubjectiveOutcomeMeasures::class, 'assessments_process_id');
    }

    // Objective Assessment Relationships
    public function observationsGeneralExamination(): HasOne
    {
        return $this->hasOne(AssObjectiveObservationsGeneralExamination::class, 'assessments_process_id');
    }

    public function palpation(): HasOne
    {
        return $this->hasOne(AssObjectivePalpation::class, 'assessments_process_id');
    }

    public function rangeOfMotion(): HasOne
    {
        return $this->hasOne(AssObjectiveRangeOfMotion::class, 'assessments_process_id');
    }

    public function muscleStrength(): HasOne
    {
        return $this->hasOne(AssObjectiveMuscleStrength::class, 'assessments_process_id');
    }

    public function neurologicalExamination(): HasOne
    {
        return $this->hasOne(AssObjectiveNeurologicalExamination::class, 'assessments_process_id');
    }

    public function specialTests(): HasOne
    {
        return $this->hasOne(AssObjectiveSpecialTests::class, 'assessments_process_id');
    }

    public function functionalAssessment(): HasOne
    {
        return $this->hasOne(AssObjectiveFunctionalAssessment::class, 'assessments_process_id');
    }

    public function jointMobility(): HasOne
    {
        return $this->hasOne(AssObjectiveJointMobility::class, 'assessments_process_id');
    }

    public function objectiveOutcomeMeasures(): HasOne
    {
        return $this->hasOne(AssObjectiveOutcomeMeasures::class, 'assessments_process_id');
    }
}

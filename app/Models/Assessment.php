<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Assessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function assessmentType(): BelongsTo
    {
        return $this->belongsTo(AssessmentType::class);
    }

    // Subjective Assessment Relationships
    public function basicPatientDetails(): HasOne
    {
        return $this->hasOne(AssSubjectiveBasicPatientDetails::class);
    }

    public function chiefComplaint(): HasOne
    {
        return $this->hasOne(AssSubjectiveChiefComplaint::class);
    }

    public function painCharacteristics(): HasOne
    {
        return $this->hasOne(AssSubjectivePainCharacteristics::class);
    }

    public function historyPresentCondition(): HasOne
    {
        return $this->hasOne(AssSubjectiveHistoryPresentCondition::class);
    }

    public function functionalLimitations(): HasOne
    {
        return $this->hasOne(AssSubjectiveFunctionalLimitations::class);
    }

    public function redFlagScreening(): HasOne
    {
        return $this->hasOne(AssSubjectiveRedFlagScreening::class);
    }

    public function yellowFlags(): HasOne
    {
        return $this->hasOne(AssSubjectiveYellowFlags::class);
    }

    public function medicalHistory(): HasOne
    {
        return $this->hasOne(AssSubjectiveMedicalHistory::class);
    }

    public function lifestyleSocialHistory(): HasOne
    {
        return $this->hasOne(AssSubjectiveLifestyleSocialHistory::class);
    }

    public function iceAssessment(): HasOne
    {
        return $this->hasOne(AssSubjectiveIceAssessment::class);
    }

    public function regionSpecific(): HasOne
    {
        return $this->hasOne(AssSubjectiveRegionSpecific::class);
    }

    public function subjectiveOutcomeMeasures(): HasOne
    {
        return $this->hasOne(AssSubjectiveOutcomeMeasures::class);
    }

    // Objective Assessment Relationships
    public function observationsGeneralExamination(): HasOne
    {
        return $this->hasOne(AssObjectiveObservationsGeneralExamination::class);
    }

    public function palpation(): HasOne
    {
        return $this->hasOne(AssObjectivePalpation::class);
    }

    public function rangeOfMotion(): HasOne
    {
        return $this->hasOne(AssObjectiveRangeOfMotion::class);
    }

    public function muscleStrength(): HasOne
    {
        return $this->hasOne(AssObjectiveMuscleStrength::class);
    }

    public function neurologicalExamination(): HasOne
    {
        return $this->hasOne(AssObjectiveNeurologicalExamination::class);
    }

    public function specialTests(): HasOne
    {
        return $this->hasOne(AssObjectiveSpecialTests::class);
    }

    public function functionalAssessment(): HasOne
    {
        return $this->hasOne(AssObjectiveFunctionalAssessment::class);
    }

    public function jointMobility(): HasOne
    {
        return $this->hasOne(AssObjectiveJointMobility::class);
    }

    public function objectiveOutcomeMeasures(): HasOne
    {
        return $this->hasOne(AssObjectiveOutcomeMeasures::class);
    }

    public function objectiveRedFlags(): HasOne
    {
        return $this->hasOne(AssObjectiveRedFlags::class);
    }
}


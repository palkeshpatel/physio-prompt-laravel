<?php

namespace App\Http\Controllers\Api\Physio;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\UserSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SubjectiveAssessmentController extends Controller
{
    /**
     * Unified endpoint to update any subjective section
     * PUT /api/physio/assessments/subjective/section
     * Body: { assessment_id, section_name, section_data }
     */
    public function updateSection(Request $request)
    {
        try {
            // Ensure user is authenticated
            $user = $request->user();
            if (!$user) {
                return response()->json([
                    'message' => 'Unauthenticated.',
                ], 401);
            }

        $sectionName = $request->section_name;
        $sectionData = $request->section_data;

        // For basic_details: Create assessment if it doesn't exist
        // For other sections: Require assessment_id and check if basic_details is completed
        if ($sectionName === 'basic_details') {
            // Validate section_name and section_data only
            $request->validate([
                'section_name' => 'required|string|in:basic_details',
                'section_data' => 'required|array',
            ]);
            
            // Create or get assessment when basic_details is submitted
            $assessmentId = $request->input('assessment_id');
            
            if ($assessmentId) {
                $assessment = Assessment::where('user_id', $user->id)
                    ->findOrFail($assessmentId);
            } else {
                // Create new assessment when basic_details is submitted for the first time
                $assessment = $this->createAssessmentForUser($user);
            }
        } else {
            // For other sections, require assessment_id and check if basic_details exists
            $request->validate([
                'assessment_id' => 'required|exists:assessments,id',
                'section_name' => 'required|string|in:chief_complaint,pain_characteristics,history_present,functional_limitations,red_flags,yellow_flags,medical_history,lifestyle,ice,region_specific,outcome_measures',
                'section_data' => 'required|array',
            ]);
            
            $assessment = Assessment::where('user_id', $user->id)
                ->findOrFail($request->assessment_id);
            
            // Check if basic_details is completed before allowing other sections
            if (!$assessment->subjective_basic_details || empty($assessment->subjective_basic_details)) {
                return response()->json([
                    'message' => 'Please complete Basic Patient Details first before proceeding to other sections.',
                ], 403);
            }
        }

        // Normalize null values: empty string for strings, 0 for integers
        $sectionData = $this->normalizeNullValues($sectionData);

        // Map section name to column name
        $columnName = $this->getColumnNameForSection($sectionName);

        // Special handling for basic_details section - extract patient details
        if ($sectionName === 'basic_details') {
            $this->extractPatientDetails($assessment, $sectionData);
        }

            // Update the JSON column
            $assessment->$columnName = $sectionData;
            $assessment->status = 'in_progress';
            
            // Recalculate completion percentages
            $assessment->calculateCompletionPercentage();
            
            // Save the model with updated percentages
            $assessment->save();

        return response()->json([
            'message' => ucfirst(str_replace('_', ' ', $sectionName)) . ' saved successfully',
            'assessment' => $assessment->fresh(),
            'assessment_id' => $assessment->id, // Always return assessment_id for frontend
        ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            $userId = $request->user() ? $request->user()->id : null;
            Log::error('Assessment not found', [
                'assessment_id' => $request->assessment_id ?? null,
                'user_id' => $userId,
            ]);
            return response()->json([
                'message' => 'Assessment not found',
            ], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            $userId = $request->user() ? $request->user()->id : null;
            Log::error('Error saving subjective section', [
                'assessment_id' => $request->assessment_id ?? null,
                'section_name' => $request->section_name ?? null,
                'user_id' => $userId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        return response()->json([
                'message' => 'Failed to save section. Please try again.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Create a new assessment for the user
     */
    private function createAssessmentForUser($user): Assessment
    {
        // Check subscription limits
        $currentMonth = now()->month;
        $currentYear = now()->year;

        $usage = DB::table('user_assessment_usage')
            ->where('user_id', $user->id)
            ->where('month', $currentMonth)
            ->where('year', $currentYear)
            ->first();

        $activeSubscription = UserSubscription::where('user_id', $user->id)
            ->where('status', 'active')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->with('subscriptionPlan')
            ->first();

        if (!$activeSubscription) {
            throw new \Exception('No active subscription found');
        }

        $limit = $activeSubscription->assessment_of_month;
        $used = $usage ? $usage->assessments_used : 0;

        if ($used >= $limit && !$activeSubscription->subscriptionPlan->unlimited_assessments) {
            throw new \Exception('Monthly assessment limit reached');
        }

        // Create assessment
        return Assessment::create([
            'user_id' => $user->id,
            'status' => 'draft',
            'completion_percentage' => 0,
            'subjective_completion_percentage' => 0,
            'objective_completion_percentage' => 0,
        ]);
    }

    /**
     * Extract patient basic details from basic_details section
     */
    private function extractPatientDetails(Assessment $assessment, array $sectionData): void
    {
        // Update patient_name, patient_age, etc. (existing columns)
        $assessment->patient_name = $sectionData['full_name'] ?? '';
        $assessment->patient_age = isset($sectionData['age']) && $sectionData['age'] !== null ? (int)$sectionData['age'] : 0;
        $assessment->patient_gender = $sectionData['gender'] ?? '';
        $assessment->patient_occupation = $sectionData['occupation'] ?? '';
        
        // Also update new columns: full_name, age, gender, occupation
        $assessment->full_name = $sectionData['full_name'] ?? '';
        $assessment->age = isset($sectionData['age']) && $sectionData['age'] !== null ? (int)$sectionData['age'] : 0;
        $assessment->gender = $sectionData['gender'] ?? '';
        $assessment->occupation = $sectionData['occupation'] ?? '';
    }

    /**
     * Normalize null values in section data
     * Converts null strings to empty strings and null integers to 0
     */
    private function normalizeNullValues(array $data): array
    {
        $normalized = [];
        
        // Fields that should be integers (0 if null)
        $integerFields = ['age', 'id', 'count', 'number', 'quantity', 'amount'];
        
        // Fields that should be floats/decimals (0 if null)
        $numericFields = ['height', 'weight', 'score', 'value', 'amount', 'price'];
        
        foreach ($data as $key => $value) {
            if ($value === null) {
                // Check if key suggests it's a numeric field
                if (in_array($key, $integerFields)) {
                    $normalized[$key] = 0;
                } elseif (in_array($key, $numericFields)) {
                    $normalized[$key] = 0.0;
                } else {
                    // Default to empty string for other fields
                    $normalized[$key] = '';
    }
            } elseif (is_array($value)) {
                // Recursively normalize nested arrays
                $normalized[$key] = $this->normalizeNullValues($value);
            } elseif (is_numeric($value)) {
                // Convert numeric strings to appropriate type
                if (in_array($key, $integerFields) || (is_numeric($value) && (int)$value == $value)) {
                    $normalized[$key] = (int)$value;
                } elseif (in_array($key, $numericFields)) {
                    $normalized[$key] = (float)$value;
                } else {
                    $normalized[$key] = $value;
                }
            } else {
                $normalized[$key] = $value;
            }
        }
        
        return $normalized;
    }

    /**
     * Map section name to column name
     */
    private function getColumnNameForSection(string $sectionName): string
    {
        $mapping = [
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
        ];

        return $mapping[$sectionName] ?? $sectionName;
    }
}

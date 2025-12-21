# PhysioPrompt API Documentation

## Base URL

```
http://localhost:8000/api/physio
```

## Authentication

All protected routes require authentication using Laravel Sanctum. Include the token in the Authorization header:

```
Authorization: Bearer {token}
```

---

## Authentication Endpoints

### 1. Register User

**POST** `/register`

**Request Body:**

```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "phone": "+919876543210",
    "password": "password123",
    "password_confirmation": "password123",
    "role_id": 1,
    "referred_by": "REF12345"
}
```

**Response (201):**

```json
{
    "message": "User registered successfully",
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "role": {
            "id": 1,
            "name": "Doctor",
            "slug": "doctor"
        }
    },
    "token": "1|xxxxxxxxxxxx"
}
```

---

### 2. Login

**POST** `/login`

**Request Body:**

```json
{
    "email": "john@example.com",
    "password": "password123"
}
```

**Response (200):**

```json
{
    "message": "Login successful",
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com"
    },
    "token": "1|xxxxxxxxxxxx"
}
```

---

### 3. Logout

**POST** `/logout`

**Headers:**

```
Authorization: Bearer {token}
```

**Response (200):**

```json
{
    "message": "Logged out successfully"
}
```

---

### 4. Get Current User

**GET** `/user`

**Headers:**

```
Authorization: Bearer {token}
```

**Response (200):**

```json
{
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "role": {
            "id": 1,
            "name": "Doctor"
        },
        "activeSubscription": {
            "id": 1,
            "subscriptionPlan": {
                "name": "Basic Plan",
                "price": 999.0
            }
        }
    }
}
```

---

## Assessment Endpoints

### 5. List Assessments

**GET** `/assessments`

**Headers:**

```
Authorization: Bearer {token}
```

**Response (200):**

```json
{
    "data": [
        {
            "id": 1,
            "user_id": 1,
            "assessment_type_id": 1,
            "status": "draft",
            "completion_percentage": 25.5,
            "created_at": "2024-01-01T00:00:00.000000Z"
        }
    ]
}
```

---

### 6. Create Assessment

**POST** `/assessments`

**Headers:**

```
Authorization: Bearer {token}
```

**Request Body:**

```json
{
    "assessment_type_id": 1
}
```

**Response (201):**

```json
{
    "message": "Assessment created successfully",
    "assessment": {
        "id": 1,
        "user_id": 1,
        "assessment_type_id": 1,
        "status": "draft",
        "completion_percentage": 0
    }
}
```

---

### 7. Get Assessment

**GET** `/assessments/{id}`

**Headers:**

```
Authorization: Bearer {token}
```

**Response (200):**

```json
{
    "id": 1,
    "user_id": 1,
    "assessment_type_id": 1,
    "status": "in_progress",
    "completion_percentage": 45.5,
    "basicPatientDetails": {
        "full_name": "John Doe",
        "age": 35,
        "gender": "Male"
    },
    "chiefComplaint": {
        "chief_complaint": "Lower back pain",
        "onset": "Gradual"
    }
}
```

---

### 8. Update Assessment

**PUT** `/assessments/{id}`

**Headers:**

```
Authorization: Bearer {token}
```

**Request Body:**

```json
{
    "status": "completed",
    "clinical_impression": "Muscle strain",
    "rehab_program": "Rest and physical therapy"
}
```

**Response (200):**

```json
{
    "message": "Assessment updated successfully",
    "assessment": {
        "id": 1,
        "status": "completed"
    }
}
```

---

### 9. Delete Assessment

**DELETE** `/assessments/{id}`

**Headers:**

```
Authorization: Bearer {token}
```

**Response (200):**

```json
{
    "message": "Assessment deleted successfully"
}
```

---

## Subjective Assessment Sections

### 10. Basic Patient Details

**POST** `/assessments/subjective/basic-patient-details`

**Headers:**

```
Authorization: Bearer {token}
```

**Request Body:**

```json
{
    "assessment_id": 1,
    "full_name": "John Doe",
    "age": 35,
    "gender": "Male",
    "height": 175.5,
    "weight": 75.0,
    "dominance": "Right",
    "occupation": "Software Engineer",
    "activity_level": "Moderate",
    "additional_info": "No additional info"
}
```

---

### 11. Chief Complaint

**POST** `/assessments/subjective/chief-complaint`

**Request Body:**

```json
{
    "assessment_id": 1,
    "chief_complaint": "Lower back pain for 2 weeks",
    "onset": "Gradual",
    "onset_date": "2024-01-15 10:00:00",
    "symptoms": ["pain", "stiffness"],
    "additional_info": "Pain worsens in the morning"
}
```

---

### 12. Pain Characteristics

**POST** `/assessments/subjective/pain-characteristics`

**Request Body:**

```json
{
    "assessment_id": 1,
    "pain_location": "Lower back, L4-L5 region",
    "pain_type": "Dull ache",
    "pain_scale": 6,
    "pain_pattern": "Constant",
    "aggravating_factors": "Sitting for long periods",
    "easing_factors": "Rest and heat therapy"
}
```

---

### 13. History of Present Condition

**POST** `/assessments/subjective/history-present-condition`

**Request Body:**

```json
{
    "assessment_id": 1,
    "duration": "2 weeks",
    "progression": "Worsening",
    "previous_episodes": "Similar episode 6 months ago",
    "mechanism_injury": "Lifting heavy object",
    "initial_treatment": "Rest and OTC painkillers"
}
```

---

### 14. Functional Limitations

**POST** `/assessments/subjective/functional-limitations`

**Request Body:**

```json
{
    "assessment_id": 1,
    "daily_activities": "Difficulty bending",
    "work_activities": "Cannot sit for more than 30 minutes",
    "recreational_activities": "Unable to play sports",
    "sleep_disturbance": "Pain wakes up at night"
}
```

---

### 15. Red Flag Screening

**POST** `/assessments/subjective/red-flag-screening`

**Request Body:**

```json
{
    "assessment_id": 1,
    "red_flags": ["bowel_bladder", "numbness"],
    "red_flag_present": true,
    "red_flag_details": "Numbness in right leg"
}
```

---

### 16. Yellow Flags

**POST** `/assessments/subjective/yellow-flags`

**Request Body:**

```json
{
    "assessment_id": 1,
    "yellow_flags": ["fear_avoidance", "catastrophizing"],
    "yellow_flag_present": true,
    "yellow_flag_details": "Patient fears movement"
}
```

---

### 17. Medical History

**POST** `/assessments/subjective/medical-history`

**Request Body:**

```json
{
    "assessment_id": 1,
    "past_medical_history": "Hypertension, Diabetes",
    "surgeries": "Appendectomy 2010",
    "medications": "Metformin, Lisinopril",
    "allergies": "Penicillin",
    "family_history": "Father had back problems"
}
```

---

### 18. Lifestyle & Social History

**POST** `/assessments/subjective/lifestyle-social-history`

**Request Body:**

```json
{
    "assessment_id": 1,
    "job_demands": { "physical": "low", "mental": "high" },
    "work_hours": "40 hours/week",
    "smoking": "Non-smoker",
    "alcohol": "Occasional",
    "exercise": "Gym 3 times/week",
    "hobbies": "Reading, hiking"
}
```

---

### 19. ICE Assessment

**POST** `/assessments/subjective/ice-assessment`

**Request Body:**

```json
{
    "assessment_id": 1,
    "ideas": "Thinks it's a muscle strain",
    "concerns": "Worried about long-term disability",
    "expectations": "Wants to return to work in 1 week"
}
```

---

### 20. Region Specific

**POST** `/assessments/subjective/region-specific`

**Request Body:**

```json
{
    "assessment_id": 1,
    "region": "Lumbar",
    "region_data": {
        "specific_area": "L4-L5",
        "radiation": "Right leg"
    }
}
```

---

### 21. Subjective Outcome Measures

**POST** `/assessments/subjective/outcome-measures`

**Request Body:**

```json
{
    "assessment_id": 1,
    "outcome_measures": ["ODI", "NPRS"],
    "scores": {
        "ODI": 45,
        "NPRS": 6
    }
}
```

---

## Objective Assessment Sections

### 22. Observations & General Examination

**POST** `/assessments/objective/observations-general-examination`

**Request Body:**

```json
{
    "assessment_id": 1,
    "posture_data": {
        "standing": "Slight forward lean",
        "sitting": "Slouched"
    },
    "gait_observation": {
        "pattern": "Antalgic",
        "speed": "Reduced"
    },
    "visual_inspection": "Mild swelling noted"
}
```

---

### 23. Palpation

**POST** `/assessments/objective/palpation`

**Request Body:**

```json
{
    "assessment_id": 1,
    "tenderness": {
        "L4": "Moderate",
        "L5": "Mild"
    },
    "temperature": "Normal",
    "swelling": "Mild",
    "tissue_texture": {
        "muscle": "Tight",
        "skin": "Normal"
    },
    "crepitus": "None"
}
```

---

### 24. Range of Motion

**POST** `/assessments/objective/range-of-motion`

**Request Body:**

```json
{
    "assessment_id": 1,
    "active_rom": {
        "flexion": 45,
        "extension": 10
    },
    "passive_rom": {
        "flexion": 50,
        "extension": 15
    },
    "pain_during_arom": true,
    "pain_location_arom": "Lower back",
    "end_feel": "Firm",
    "comparison_other_side": "Reduced compared to left"
}
```

---

### 25. Muscle Strength

**POST** `/assessments/objective/muscle-strength`

**Request Body:**

```json
{
    "assessment_id": 1,
    "mmt_scores": {
        "quadriceps": "4/5",
        "hamstrings": "4/5"
    },
    "core_activation": "Fair",
    "pain_on_resistance": true,
    "pain_movement": "Extension",
    "functional_tests": {
        "squat": "Limited",
        "single_leg_stand": "Unable"
    }
}
```

---

### 26. Neurological Examination

**POST** `/assessments/objective/neurological-examination`

**Request Body:**

```json
{
    "assessment_id": 1,
    "sensation": {
        "L4": "Intact",
        "L5": "Reduced"
    },
    "reflexes": {
        "patellar": "2+",
        "achilles": "1+"
    },
    "myotomes": {
        "L4": "4/5",
        "L5": "3/5"
    },
    "neural_tension": {
        "SLR": "Positive at 45 degrees"
    }
}
```

---

### 27. Special Tests

**POST** `/assessments/objective/special-tests`

**Request Body:**

```json
{
    "assessment_id": 1,
    "cervical_tests": {
        "spurling": "Negative"
    },
    "lumbar_tests": {
        "slr": "Positive",
        "femoral_stretch": "Negative"
    },
    "shoulder_tests": {},
    "other_tests": {
        "fabere": "Negative"
    }
}
```

---

### 28. Functional Assessment

**POST** `/assessments/objective/functional-assessment`

**Request Body:**

```json
{
    "assessment_id": 1,
    "functional_data": {
        "sit_to_stand": "Limited",
        "walking": "Antalgic"
    },
    "movement_quality": "Compensatory patterns noted",
    "functional_scores": {
        "TUG": "15 seconds"
    }
}
```

---

### 29. Joint Mobility

**POST** `/assessments/objective/joint-mobility`

**Request Body:**

```json
{
    "assessment_id": 1,
    "joint_data": {
        "L4-L5": "Hypomobile",
        "L5-S1": "Normal"
    },
    "mobility_scores": {
        "L4-L5": "2/5",
        "L5-S1": "4/5"
    }
}
```

---

### 30. Objective Outcome Measures

**POST** `/assessments/objective/outcome-measures`

**Request Body:**

```json
{
    "assessment_id": 1,
    "outcome_measures": ["ROM", "Strength"],
    "scores": {
        "ROM": 45,
        "Strength": 4
    }
}
```

---

### 31. Objective Red Flags

**POST** `/assessments/objective/red-flags`

**Request Body:**

```json
{
    "assessment_id": 1,
    "red_flags": ["cauda_equina", "fracture"],
    "red_flag_present": false,
    "red_flag_details": "None noted"
}
```

---

## Subscription Endpoints

### 32. Get Subscription Plans

**GET** `/subscriptions/plans`

**Headers:**

```
Authorization: Bearer {token}
```

**Response (200):**

```json
[
    {
        "id": 1,
        "name": "Free Plan",
        "slug": "free",
        "price": 0.0,
        "features": ["3 assessments per month"],
        "free_assessments_limit": 3
    },
    {
        "id": 2,
        "name": "Basic Plan",
        "slug": "basic",
        "price": 999.0,
        "features": ["100 assessments per month", "PDF download"],
        "free_assessments_limit": 100
    }
]
```

---

### 33. Get Current Subscription

**GET** `/subscriptions/current`

**Headers:**

```
Authorization: Bearer {token}
```

**Response (200):**

```json
{
    "subscription": {
        "id": 1,
        "status": "active",
        "start_date": "2024-01-01",
        "end_date": "2024-02-01",
        "subscriptionPlan": {
            "name": "Basic Plan",
            "price": 999.0
        }
    }
}
```

---

### 34. Subscribe to Plan

**POST** `/subscriptions/subscribe`

**Headers:**

```
Authorization: Bearer {token}
```

**Request Body:**

```json
{
    "subscription_plan_id": 2,
    "payment_id": "pay_123456",
    "amount_paid": 999.0
}
```

**Response (201):**

```json
{
    "message": "Subscription activated successfully",
    "subscription": {
        "id": 1,
        "status": "active"
    }
}
```

---

### 35. Get Usage

**GET** `/subscriptions/usage`

**Headers:**

```
Authorization: Bearer {token}
```

**Response (200):**

```json
{
    "usage": {
        "month": 1,
        "year": 2024,
        "assessments_used": 5,
        "assessment_limit": 100
    },
    "limit": 100,
    "used": 5,
    "remaining": 95
}
```

---

## Admin Endpoints

### 36. Get All Users

**GET** `/admin/users`

**Headers:**

```
Authorization: Bearer {token}
```

**Response (200):**

```json
{
    "data": [
        {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com",
            "role": {
                "name": "Doctor"
            }
        }
    ]
}
```

---

### 37. Get Active Users

**GET** `/admin/users/active`

**Headers:**

```
Authorization: Bearer {token}
```

**Response (200):**

```json
{
    "data": [
        {
            "id": 1,
            "name": "John Doe",
            "activeSubscription": {
                "status": "active"
            }
        }
    ]
}
```

---

### 38. Create Subscription Plan

**POST** `/admin/plans`

**Headers:**

```
Authorization: Bearer {token}
```

**Request Body:**

```json
{
    "name": "Pro Plan",
    "slug": "pro",
    "price": 1999.0,
    "description": "Professional plan",
    "features": ["Unlimited assessments", "All features"],
    "free_assessments_limit": 0,
    "unlimited_assessments": true,
    "ad_free": true,
    "pdf_download": true,
    "ai_impression": true,
    "ai_rehab_program": true,
    "reassessment_enabled": true,
    "is_active": true
}
```

**Response (201):**

```json
{
    "message": "Subscription plan created successfully",
    "plan": {
        "id": 4,
        "name": "Pro Plan"
    }
}
```

---

## Error Responses

All endpoints may return the following error responses:

### 400 Bad Request

```json
{
    "message": "Validation failed",
    "errors": {
        "email": ["The email field is required."]
    }
}
```

### 401 Unauthorized

```json
{
    "message": "Unauthenticated."
}
```

### 403 Forbidden

```json
{
    "message": "Monthly assessment limit reached",
    "limit": 3,
    "used": 3
}
```

### 404 Not Found

```json
{
    "message": "Assessment not found"
}
```

### 422 Unprocessable Entity

```json
{
    "message": "The given data was invalid.",
    "errors": {
        "email": ["The email has already been taken."]
    }
}
```

---

## Test Users

After running seeders, you can use these test accounts:

**Doctors:**

-   Email: `doctor1@example.com` - Password: `password123`
-   Email: `doctor2@example.com` - Password: `password123`
-   Email: `doctor3@example.com` - Password: `password123`

**Patients:**

-   Email: `patient1@example.com` - Password: `password123`
-   Email: `patient2@example.com` - Password: `password123`
-   Email: `patient3@example.com` - Password: `password123`

---

## Setup Instructions

1. Install Laravel Sanctum:

```bash
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate
```

2. Run migrations:

```bash
php artisan migrate
```

3. Run seeders:

```bash
php artisan db:seed
```

4. Start the server:

```bash
php artisan serve
```

---

## Notes

-   All timestamps are in UTC
-   All monetary values are in INR (₹)
-   Assessment completion percentage is calculated automatically
-   Monthly assessment limits reset at the start of each month
-   Subscriptions expire after 1 month from start date

<?php

namespace Database\Seeders;

use App\Models\AssessmentType;
use Illuminate\Database\Seeder;

class AssessmentTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            [
                'name' => 'Subjective',
                'slug' => 'subjective',
                'description' => 'Patient-reported information through interviews and questionnaires',
                'total_sections' => 12,
                'is_active' => true,
            ],
            [
                'name' => 'Objective',
                'slug' => 'objective',
                'description' => 'Physical examination and clinical tests performed by healthcare professionals',
                'total_sections' => 10,
                'is_active' => true,
            ],
        ];

        foreach ($types as $type) {
            AssessmentType::updateOrCreate(
                ['slug' => $type['slug']],
                $type
            );
        }
    }
}




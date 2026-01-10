<?php

namespace Database\Seeders;

use App\Models\AppStatistic;
use Illuminate\Database\Seeder;

class AppStatisticsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing statistics if any
        AppStatistic::truncate();

        // Seed the 4 fixed statistics rows
        $statistics = [
            [
                'type' => 'patients',
                'icon' => 'Users',
                'title' => 'Patients',
                'count' => '2,500+',
                'sort_order' => 1,
            ],
            [
                'type' => 'clinics',
                'icon' => 'Building2',
                'title' => 'Clinics',
                'count' => '50+',
                'sort_order' => 2,
            ],
            [
                'type' => 'countries',
                'icon' => 'Globe',
                'title' => 'Countries',
                'count' => '12',
                'sort_order' => 3,
            ],
            [
                'type' => 'years',
                'icon' => 'Calendar',
                'title' => 'Years',
                'count' => '10+',
                'sort_order' => 4,
            ],
        ];

        foreach ($statistics as $stat) {
            AppStatistic::create($stat);
        }
    }
}

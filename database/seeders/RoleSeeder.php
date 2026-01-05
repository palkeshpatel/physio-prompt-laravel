<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Doctor',
                'slug' => 'doctor',
                'description' => 'Healthcare professional who can create assessments',
            ],
            [
                'name' => 'Patient',
                'slug' => 'patient',
                'description' => 'Patient who can view their assessments',
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}




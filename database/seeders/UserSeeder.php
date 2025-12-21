<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\UserSubscription;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $doctorRole = Role::where('slug', 'doctor')->first();
        $patientRole = Role::where('slug', 'patient')->first();

        // Create 5 doctors
        for ($i = 1; $i <= 5; $i++) {
            $referralCode = Str::upper(Str::random(8));
            while (User::where('referral_code', $referralCode)->exists()) {
                $referralCode = Str::upper(Str::random(8));
            }

            $user = User::create([
                'name' => "Doctor {$i}",
                'email' => "doctor{$i}@example.com",
                'phone' => "+9198765432{$i}",
                'password' => Hash::make('password123'),
                'role_id' => $doctorRole->id,
                'referral_code' => $referralCode,
                'email_verified_at' => now(),
            ]);

            // Assign subscription to some doctors
            if ($i <= 2) {
                UserSubscription::create([
                    'user_id' => $user->id,
                    'subscription_plan_id' => 2, // Basic Plan
                    'start_date' => now(),
                    'end_date' => now()->addMonth(),
                    'status' => 'active',
                    'assessment_of_month' => 100,
                    'amount_paid' => 999.00,
                ]);
            } elseif ($i === 3) {
                UserSubscription::create([
                    'user_id' => $user->id,
                    'subscription_plan_id' => 3, // Premium Plan
                    'start_date' => now(),
                    'end_date' => now()->addMonth(),
                    'status' => 'active',
                    'assessment_of_month' => 999999,
                    'amount_paid' => 1299.00,
                ]);
            }
        }

        // Create 10 patients
        for ($i = 1; $i <= 10; $i++) {
            $referralCode = Str::upper(Str::random(8));
            while (User::where('referral_code', $referralCode)->exists()) {
                $referralCode = Str::upper(Str::random(8));
            }

            $user = User::create([
                'name' => "Patient {$i}",
                'email' => "patient{$i}@example.com",
                'phone' => "+9191234567{$i}",
                'password' => Hash::make('password123'),
                'role_id' => $patientRole->id,
                'referral_code' => $referralCode,
                'referred_by' => $i <= 3 ? 'REF' . Str::upper(Str::random(5)) : null,
                'email_verified_at' => now(),
            ]);

            // Assign free plan to first 3 patients
            if ($i <= 3) {
                UserSubscription::create([
                    'user_id' => $user->id,
                    'subscription_plan_id' => 1, // Free Plan
                    'start_date' => now(),
                    'end_date' => now()->addMonth(),
                    'status' => 'active',
                    'assessment_of_month' => 3,
                    'amount_paid' => 0.00,
                ]);
            }
        }
    }
}


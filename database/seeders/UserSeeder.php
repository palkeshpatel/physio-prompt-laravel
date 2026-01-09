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
    }
}




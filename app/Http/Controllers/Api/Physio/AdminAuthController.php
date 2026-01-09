<?php

namespace App\Http\Controllers\Api\Physio;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AdminAuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $admin = Admin::where('email', $request->email)->first();

        if (!$admin || !Hash::check($request->password, $admin->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $admin->createToken('admin-auth-token')->plainTextToken;

        return response()->json([
            'message' => 'Admin login successful',
            'admin' => $admin,
            'token' => $token,
        ]);
    }

    public function logout(Request $request)
    {
        $user = $request->user();

        if ($user) {
            $user->currentAccessToken()->delete();
        }

        return response()->json([
            'message' => 'Admin logged out successfully',
        ]);
    }

    public function admin(Request $request)
    {
        return response()->json([
            'admin' => $request->user(),
        ]);
    }

    /**
     * Change password for the authenticated admin.
     */
    public function changePassword(Request $request)
    {
        $admin = $request->user();

        $request->validate([
            'old_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Verify old password
        if (!Hash::check($request->old_password, $admin->password)) {
            return response()->json([
                'message' => 'The old password is incorrect.',
            ], 422);
        }

        // Update password
        $admin->update([
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'message' => 'Password changed successfully',
        ]);
    }
}

<?php

namespace App\Http\Controllers\Api\Physio;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminCrudController extends Controller
{
    /**
     * Display a listing of admins.
     */
    public function index(Request $request)
    {
        $query = Admin::query();

        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $admins = $query->orderBy('created_at', 'desc')->paginate(15);

        return response()->json($admins);
    }

    /**
     * Store a newly created admin.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8',
            'avatar' => 'nullable|string|max:255',
        ]);

        $admin = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'avatar' => $request->avatar,
        ]);

        return response()->json([
            'message' => 'Admin created successfully',
            'admin' => $admin,
        ], 201);
    }

    /**
     * Display the specified admin.
     */
    public function show($id)
    {
        $admin = Admin::findOrFail($id);

        return response()->json($admin);
    }

    /**
     * Update the specified admin.
     */
    public function update(Request $request, $id)
    {
        $admin = Admin::findOrFail($id);

        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => ['sometimes', 'required', 'string', 'email', 'max:255', Rule::unique('admins')->ignore($admin->id)],
            'phone' => 'nullable|string|max:20',
            'password' => 'sometimes|string|min:8',
            'avatar' => 'nullable|string|max:255',
        ]);

        $data = $request->only(['name', 'email', 'phone', 'avatar']);

        if ($request->has('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $admin->update($data);

        return response()->json([
            'message' => 'Admin updated successfully',
            'admin' => $admin->fresh(),
        ]);
    }

    /**
     * Remove the specified admin.
     */
    public function destroy($id)
    {
        $admin = Admin::findOrFail($id);
        $admin->delete();

        return response()->json([
            'message' => 'Admin deleted successfully',
        ]);
    }
}


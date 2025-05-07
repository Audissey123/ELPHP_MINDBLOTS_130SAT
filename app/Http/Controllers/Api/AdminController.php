<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function createAdmin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'phone' => 'required|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $admin = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'role' => 'admin',
                'api_token' => Str::random(60),
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Admin user created successfully',
                'data' => [
                    'admin' => $admin
                ]
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error creating admin user',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function listAdmins()
    {
        try {
            $admins = User::where('role', 'admin')->get();
            return response()->json([
                'status' => 'success',
                'data' => [
                    'admins' => $admins
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error retrieving admin list',
                'error' => $e->getMessage()
            ], 500);
        }
    }
} 
<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class FarmerController extends Controller
{
    public function register(Request $request)
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
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'role' => 'farmer',
                'api_token' => Str::random(60),
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Farmer registered successfully',
                'data' => [
                    'user' => $user
                ]
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error registering farmer',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function index()
    {
        try {
            $farmers = User::where('role', 'farmer')->get();
            return response()->json([
                'status' => 'success',
                'data' => [
                    'farmers' => $farmers
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error retrieving farmers list',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $farmer = User::where('role', 'farmer')->findOrFail($id);
            return response()->json([
                'status' => 'success',
                'data' => [
                    'farmer' => $farmer
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error retrieving farmer details',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'farmer_fname' => 'required|string|max:30',
            'farmer_lname' => 'required|string|max:30',
            'farmer_contact' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $farmer = User::create($request->all());
            return response()->json([
                'status' => 'success',
                'message' => 'Farmer created successfully',
                'data' => [
                    'farmer' => $farmer
                ]
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error creating farmer',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, User $farmer)
    {
        $validator = Validator::make($request->all(), [
            'farmer_fname' => 'required|string|max:30',
            'farmer_lname' => 'required|string|max:30',
            'farmer_contact' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $farmer->update($request->all());
            return response()->json([
                'status' => 'success',
                'message' => 'Farmer updated successfully',
                'data' => [
                    'farmer' => $farmer
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error updating farmer',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(User $farmer)
    {
        try {
            $farmer->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Farmer deleted successfully'
            ], 204);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error deleting farmer',
                'error' => $e->getMessage()
            ], 500);
        }
    }
} 
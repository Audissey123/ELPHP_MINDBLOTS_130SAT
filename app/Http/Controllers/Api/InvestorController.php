<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class InvestorController extends Controller
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
                'role' => 'investor',
                'api_token' => Str::random(60),
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Investor registered successfully',
                'data' => [
                    'user' => $user
                ]
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error registering investor',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function index()
    {
        try {
            $investors = User::where('role', 'investor')->get();
            return response()->json([
                'status' => 'success',
                'data' => [
                    'investors' => $investors
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error retrieving investors list',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $investor = User::where('role', 'investor')->findOrFail($id);
            return response()->json([
                'status' => 'success',
                'data' => [
                    'investor' => $investor
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error retrieving investor details',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'investor_fname' => 'required|string|max:30',
            'investor_lname' => 'required|string|max:30',
            'investor_contact' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $investor = User::create($request->all());
            return response()->json([
                'status' => 'success',
                'message' => 'Investor created successfully',
                'data' => [
                    'investor' => $investor
                ]
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error creating investor',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, User $investor)
    {
        $validator = Validator::make($request->all(), [
            'investor_fname' => 'required|string|max:30',
            'investor_lname' => 'required|string|max:30',
            'investor_contact' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $investor->update($request->all());
            return response()->json([
                'status' => 'success',
                'message' => 'Investor updated successfully',
                'data' => [
                    'investor' => $investor
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error updating investor',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(User $investor)
    {
        try {
            $investor->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Investor deleted successfully'
            ], 204);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error deleting investor',
                'error' => $e->getMessage()
            ], 500);
        }
    }
} 
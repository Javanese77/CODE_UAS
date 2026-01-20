<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            // Simple return for student project
            // In production, return $user->createToken('MyApp')->plainTextToken with Sanctum
            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'data' => $user,
                'token' => 'dummy-token-for-session-simulation', // Placeholder if no Sanctum
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Unauthorized'
        ], 401);
    }
}

<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController
{
    public function __construct(protected AuthService $authService)
    {
    }

    /**
     * Register a new user
     * POST /api/auth/register
     */
    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = $this->authService->register($validated);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Registration failed',
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'User registered successfully',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
        ], 201);
    }

    /**
     * Login user and return JWT token
     * POST /api/auth/login
     */
    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $token = $this->authService->login($validated['email'], $validated['password']);

        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials',
            ], 401);
        }

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data' => $token,
        ], 200);
    }

    /**
     * Refresh JWT token
     * POST /api/auth/refresh
     */
    public function refresh(): JsonResponse
    {
        $token = $this->authService->refreshToken();

        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Token refresh failed',
            ], 401);
        }

        return response()->json([
            'success' => true,
            'message' => 'Token refreshed successfully',
            'data' => $token,
        ], 200);
    }

    /**
     * Logout user and invalidate token
     * POST /api/auth/logout
     */
    public function logout(): JsonResponse
    {
        $this->authService->logout();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully',
        ], 200);
    }

    /**
     * Get authenticated user profile
     * GET /api/auth/me
     */
    public function me(): JsonResponse
    {
        $user = $this->authService->getAuthenticatedUser();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to fetch user',
            ], 400);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'created_at' => $user->created_at,
            ],
        ], 200);
    }
}

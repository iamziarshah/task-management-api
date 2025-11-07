<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Exception;

class AuthService
{
    /**
     * Register a new user
     */
    public function register(array $data): ?User
    {
        try {
            return User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Attempt login and return JWT token
     */
    public function login(string $email, string $password): ?array
    {
        $credentials = ['email' => $email, 'password' => $password];

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return null;
            }

            return $this->buildTokenResponse($token);
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Refresh JWT token
     */
    public function refreshToken(): ?array
    {
        try {
            $newToken = JWTAuth::refresh();

            return $this->buildTokenResponse($newToken);
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Logout user
     */
    public function logout(): bool
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Get authenticated user
     */
    public function getAuthenticatedUser(): ?User
    {
        try {
            return JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Build token response array
     */
    private function buildTokenResponse(string $token): array
    {
        return [
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
        ];
    }
}

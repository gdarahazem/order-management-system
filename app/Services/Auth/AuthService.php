<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\AuthenticationException;

class AuthService
{
    /**
     * Register a new user
     */
    public function register(array $data): array
    {
        try {
            // Create user
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            // Create token
            $token = $user->createToken('api-token')->plainTextToken;

            return [
                'user' => $this->formatUserData($user),
                'token' => $token,
            ];

        } catch (\Exception $e) {
            throw new \Exception('User registration failed: ' . $e->getMessage());
        }
    }

    /**
     * Login user and create token
     */
    public function login(array $credentials): array
    {
        try {
            // Find user by email
            $user = User::where('email', $credentials['email'])->first();

            // Check if user exists and password is correct
            if (!$user || !Hash::check($credentials['password'], $user->password)) {
                throw new AuthenticationException('Invalid credentials');
            }

            // Create token
            $token = $user->createToken('api-token')->plainTextToken;

            return [
                'user' => $this->formatUserData($user),
                'token' => $token,
            ];

        } catch (AuthenticationException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new \Exception('Login failed: ' . $e->getMessage());
        }
    }

    /**
     * Logout user (revoke current token)
     */
    public function logout(User $user): bool
    {
        try {
            $user->currentAccessToken()->delete();
            return true;
        } catch (\Exception $e) {
            throw new \Exception('Logout failed: ' . $e->getMessage());
        }
    }

    /**
     * Format user data for response
     */
    private function formatUserData(User $user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'email_verified_at' => $user->email_verified_at,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
        ];
    }
}

<?php

// app/Http/Controllers/Api/AuthController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\Auth\AuthService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Auth\AuthenticationException;

class AuthController extends Controller
{
    use ApiResponseTrait;

    protected AuthService $authService;

    /**
     * Constructor
     */
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Register a new user
     *
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $data = $this->authService->register($request->validated());

            return $this->successResponse(
                $data,
                'User registered successfully',
                201
            );

        } catch (\Exception $e) {
            return $this->serverErrorResponse('Registration failed');
        }
    }

    /**
     * Login user and create token
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $data = $this->authService->login($request->validated());

            return $this->successResponse(
                $data,
                'Login successful'
            );

        } catch (AuthenticationException $e) {
            return $this->unauthorizedResponse('Invalid credentials');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Login failed');
        }
    }

    /**
     * Logout user (revoke current token)
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            $this->authService->logout($request->user());

            return $this->successResponse(
                null,
                'Logout successful'
            );

        } catch (\Exception $e) {
            return $this->serverErrorResponse('Logout failed');
        }
    }
}

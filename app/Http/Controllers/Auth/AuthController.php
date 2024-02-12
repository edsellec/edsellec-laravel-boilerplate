<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Routing\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Helpers\ResponseHelper;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\Auth\AuthResource;
use App\Http\Resources\Shared\UserResource;
use App\Services\Auth\AuthService;

class AuthController extends Controller
{
    private AuthService $authService;

    /**
     * AuthService constructor.
     *
     * @param AuthService $authService
     */
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Register new user to the repository.
     *
     * @param RegisterRequest $request
     * 
     * @return JsonResponse|JsonResource
     */
    public function register(RegisterRequest $request): JsonResponse|JsonResource
    {
        $responseData = $this->authService->register($request->toData());

        if ($responseData->isError()) {
            return ResponseHelper::error($responseData->getMessage());
        }

        return ResponseHelper::resource(AuthResource::class, $responseData->getData());
    }

    /**
     * Log in a user.
     *
     * @param LoginRequest $request
     * @param int $id
     * 
     * @return JsonResponse|JsonResource
     */
    public function login(LoginRequest $request): JsonResponse|JsonResource
    {
        $responseData = $this->authService->login($request->toData());

        if ($responseData->isError()) {
            return ResponseHelper::error($responseData->getMessage());
        }

        return ResponseHelper::resource(AuthResource::class, $responseData->getData());
    }

    /**
     * Log out the current authenticated user.
     * 
     * @return JsonResponse|JsonResource
     */
    public function logout(): JsonResponse|JsonResource
    {
        $responseData = $this->authService->logout();

        if ($responseData->isError()) {
            return ResponseHelper::error($responseData->getMessage());
        }

        return ResponseHelper::success($responseData->getMessage());
    }

    /**
     * Get current authenticated user.
     * 
     * @return JsonResponse|JsonResource
     */
    public function showAuthenticatedUser(): JsonResponse|JsonResource
    {
        $responseData = $this->authService->showAuthenticatedUser();

        if ($responseData->isError()) {
            return ResponseHelper::error($responseData->getMessage());
        }

        return ResponseHelper::resource(UserResource::class, $responseData->getData());
    }
}

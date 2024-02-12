<?php

namespace App\Services\Auth;

use App\Data\Auth\AuthData;
use App\Data\Shared\ResponseData;
use App\Repositories\Auth\AuthRepository;

class AuthService
{
    private AuthRepository $authRepository;

    /**
     * AuthService constructor.
     *
     * @param AuthRepository $authRepository
     */
    public function __construct(AuthRepository $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    /**
     * Register new user to the repository.
     *
     * @param AuthData $authData
     * 
     * @return ResponseData
     */
    public function register(AuthData $authData): ResponseData
    {
        $user = $this->authRepository->register($authData);
        if (empty($user)) {
            return ResponseData::error('Unable to register. Please check your input and try again.');
        }

        return ResponseData::success('New user registered successfully.', $user);
    }

    /**
     * Log in a user.
     *
     * @param AuthData $authData
     * 
     * @return ResponseData
     */
    public function login(AuthData $authData): ResponseData
    {
        $user = $this->authRepository->login($authData);
        if (empty($user)) {
            return ResponseData::error('Unable to login. Please check your input and try again.');
        }

        return ResponseData::success('Logged in successfully.', $user);
    }

    /**
     * Log out the current authenticated user.
     * 
     * @return ResponseData
     */
    public function logout(): ResponseData
    {
        $isLoggedOut = $this->authRepository->logout();
        if (!$isLoggedOut) {
            return ResponseData::error('Unable to logout. Please try again.');
        }

        return ResponseData::success('Logged out successfully.');
    }

    /**
     * Get current authenticated user.
     *
     * @param AuthData $authData
     * 
     * @return ResponseData
     */
    public function showAuthenticatedUser(): ResponseData
    {
        $user = $this->authRepository->getAuthenticatedUser();
        if (empty($user)) {
            return ResponseData::error('Unable to get authenticated user. Please try again.');
        }

        return ResponseData::map($user);
    }
}

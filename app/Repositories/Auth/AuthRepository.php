<?php

namespace App\Repositories\Auth;

use App\Data\Auth\AuthData;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthRepository
{
    /**
     * Register new user to the repository.
     *
     * @param AuthData $authData
     * 
     * @return User|null
     */
    public function register(AuthData $authData): ?User
    {
        $user ??= new User();
        $user->name = $authData->name;
        $user->email = $authData->email;
        $user->password = bcrypt($authData->password);
        $user->save();

        $user = $this->find($user->id);
        if (empty($user)) {
            return null;
        }

        $user->access_token = $user->createToken('AuthToken')->accessToken;
        return $user;
    }

    /**
     * Find user from the repository by id.
     *
     * @param string $id
     * @param array $relations
     * 
     * @return User|null
     */
    public function find(string $id, array $relations = []): ?User
    {
        return User::with($relations)->firstWhere('id', $id);
    }

    /**
     * Find user from the repository by email.
     *
     * @param string $email
     * @param array $relations
     * 
     * @return User|null
     */
    public function findByEmail(string $email, array $relations = []): ?User
    {
        return User::with($relations)->firstWhere('email', $email);
    }

    /**
     * Log in a user.
     *
     * @param AuthData $authData
     * 
     * @return User|null
     */
    public function login(AuthData $authData): ?User
    {
        $credentials = [
            'email' => $authData->email,
            'password' => $authData->password
        ];
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $user->access_token = $user->createToken('AuthToken')->accessToken;
            return $user;
        }

        return null;
    }

    /**
     * Get current authenticated user.
     * 
     * @return User|null
     */
    public function getAuthenticatedUser(): ?User
    {
        return Auth::user();
    }

    /**
     * Log out the current authenticated user.
     * 
     * @return bool
     */
    public function logout(): bool
    {
        Auth::user()->tokens->each(function ($token) {
            $token->delete();
        });

        return true;
    }
}

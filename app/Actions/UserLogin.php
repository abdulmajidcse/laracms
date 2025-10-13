<?php

namespace App\Actions;

use App\Contracts\UserRepositoryInterface;
use App\Services\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserLogin
{
    public function __construct(
        protected ApiResponse $apiResponse,
        protected UserRepositoryInterface $users
    ) {}

    public function handle(array $credentials): JsonResponse
    {
        $user = $this->users->findByEmail($credentials['email']);

        if ($user && Hash::check($credentials['password'], $user->password)) {
            $token = $user->createToken('api');

            return $this->apiResponse->success(
                ['token' => $token->plainTextToken],
                'Login Token Generated Successfully!'
            );
        }

        throw ValidationException::withMessages([
            'email'  => [__('auth.failed')]
        ]);
    }
}

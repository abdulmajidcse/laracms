<?php

namespace App\Actions;

use App\Contracts\UserRepositoryContract;
use App\Services\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserLogin
{
    public function __construct(
        protected ApiResponse $apiResponse,
        protected UserRepositoryContract $userContract
    ) {}

    public function handle(array $credentials): JsonResponse
    {
        $user = $this->userContract->findByEmail($credentials['email']);

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

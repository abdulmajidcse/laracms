<?php

namespace App\Actions;

use App\Repositories\UserRepository;
use App\Services\ApiResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserLogin
{
    public function __construct(
        protected ApiResponse $apiResponse,
        protected UserRepository $userRepository
    ) {}

    public function handle(array $credentials)
    {
        $user = $this->userRepository->findByEmail($credentials['email']);

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

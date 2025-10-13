<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\UserLogin;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\LoginRequest;
use Illuminate\Http\JsonResponse;

class LoginController extends Controller
{
    public function __construct(protected UserLogin $userLogin) {}

    public function __invoke(LoginRequest $request): JsonResponse
    {
        return $this->userLogin->handle($request->validated());
    }
}

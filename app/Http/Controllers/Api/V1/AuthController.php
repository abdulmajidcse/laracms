<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\UserLogin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\UserResource;
use App\Http\Requests\Api\V1\LoginRequest;
use App\Services\ApiResponse;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function __construct(protected ApiResponse $apiResponse) {}

    public function login(LoginRequest $request, UserLogin $userLogin): JsonResponse
    {
        return $userLogin->handle($request->validated());
    }

    public function user(Request $request): JsonResponse
    {
        return $this->apiResponse->resource(
            new UserResource($request->user()),
            "User retrieved successfully"
        );
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        $response['message'] = 'Successfully Logout!';
        return $this->apiResponse->success(message: "Logout successfully");
    }
}

<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Services\ApiResponse;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\UserResource;
use App\Http\Requests\V1\ProfileUpdateRequest;
use App\Repositories\UserRepository;

class ProfileController extends Controller
{
    public function __construct(protected ApiResponse $apiResponse) {}

    public function show(Request $request): JsonResponse
    {
        return $this->apiResponse->resource(
            new UserResource($request->user()),
            "User retrieved successfully."
        );
    }

    public function update(ProfileUpdateRequest $request, UserRepository $userRepo): JsonResponse
    {
        $userRepo->updateProfile($request->user(), $request->validated());

        return $this->apiResponse->success(message: 'Profile updated successfully.');
    }
}

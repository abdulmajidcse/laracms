<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Services\ApiResponse;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\UserResource;

class ProfileController extends Controller
{
    public function __construct(protected ApiResponse $apiResponse) {}

    public function __invoke(Request $request): JsonResponse
    {
        return $this->apiResponse->resource(
            new UserResource($request->user()),
            "User retrieved successfully"
        );
    }
}

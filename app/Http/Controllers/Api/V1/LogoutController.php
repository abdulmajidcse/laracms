<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\ApiResponse;
use Illuminate\Http\JsonResponse;

class LogoutController extends Controller
{
    public function __construct(protected ApiResponse $apiResponse) {}

    public function __invoke(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();
        return $this->apiResponse->success(message: "Logout successfully");
    }
}

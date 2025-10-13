<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\ContentRequest;
use App\Services\ContentService;
use Illuminate\Http\JsonResponse;

class ContentController extends Controller
{
    public function __construct(
        private ContentService $contentService
    ) {}

    public function index(): JsonResponse
    {
        return $this->contentService->paginate();
    }

    public function store(ContentRequest $request): JsonResponse
    {
        return $this->contentService->save($request->validated());
    }

    public function show(int $id): JsonResponse
    {
        return $this->contentService->details($id);
    }

    public function update(ContentRequest $request, int $id): JsonResponse
    {
        return $this->contentService->update($request->validated(), $id);
    }

    public function destroy(int $id): JsonResponse
    {
        return $this->contentService->delete($id);
    }
}

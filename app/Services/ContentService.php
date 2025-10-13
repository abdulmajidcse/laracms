<?php

namespace App\Services;

use App\Actions\ContentOrderAction;
use App\Contracts\ContentRepositoryContract;
use App\Http\Resources\V1\ContentResource;
use App\Models\Content;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ContentService
{
    public function __construct(
        private ContentRepositoryContract $contentContract,
        private ApiResponse $apiResponse,
        private ContentOrderAction $contentOrder
    ) {}

    public function paginate(int $perPage = 10): JsonResponse
    {
        $contents = $this->contentContract->paginate($perPage);

        return $this->apiResponse->collection(
            ContentResource::collection($contents),
            'Contents retrieved successfully.'
        );
    }

    public function save(array $data, ?Content $content = null): JsonResponse
    {
        if ($content) {
            $data['updated_by'] = Auth::id();
        } else {
            $data['order'] = $this->contentOrder->newOrder();
            $data['created_by'] = Auth::id();
        }

        $statusCode = $content ? 200 : 201;
        $code = $content ? 'updated' : 'created';
        $content = $this->contentContract->createOrUpdate($data, $content);

        return $this->apiResponse->resource(
            new ContentResource($content),
            "Content {$code} successfully.",
            $code,
            $statusCode
        );
    }

    public function details(int $id): JsonResponse
    {
        $content = $this->contentContract->findById($id);

        if (!$content) {
            return $this->notFoundResponse($id);
        }

        return $this->apiResponse->resource(
            new ContentResource($content),
            'Content retrived successfully.'
        );
    }

    public function update(array $data, int $id): JsonResponse
    {
        $content = $this->contentContract->findById($id);

        if (!$content) {
            return $this->notFoundResponse($id);
        }

        return $this->save($data, $content);
    }

    public function delete(int $id): JsonResponse
    {
        if (!$this->contentContract->findById($id)) {
            return $this->notFoundResponse($id);
        }

        $this->contentContract->delete($id);

        return $this->apiResponse->success(message: 'Content deleted successfully.', code: 'deleted');
    }

    private function notFoundResponse(int $id): JsonResponse
    {
        return $this->apiResponse->notFound("Content not found by id: {$id}.");
    }
}

<?php

namespace App\Services;

use App\Models\Content;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Contracts\ContentRepositoryContract;
use Illuminate\Support\Facades\Auth;

class ContentOrder
{
    protected int $gap = 1;

    public function __construct(
        private ContentRepositoryContract $contentContract,
        private ApiResponse $apiResponse
    ) {}

    public function newOrder(): float
    {
        $max = $this->contentContract->maxOrder();

        return $max === null ? $this->gap : (float) $max + $this->gap;
    }

    public function reorder(array $data): JsonResponse
    {
        return DB::transaction(function () use ($data) {
            $reorderContentIds = $data['reorder_content_ids'];
            $prevOrder = null;
            $nextOrder = null;

            if (!empty($data['prev_content_id'])) {
                $prevContent = $this->contentContract->findOrFail($data['prev_content_id']);
                $prevOrder = (float) $prevContent->order;
            }

            if (!empty($data['next_content_id'])) {
                $nextOrder = $this->contentContract->findOrFail($data['next_content_id']);
                $nextOrder = (float) $nextOrder->order;
            }

            if ($prevOrder === null && $nextOrder === null) {
                $prevContent = $this->contentContract->findOrFail($reorderContentIds[0]);
                $prevOrder = (float)$prevContent->order;

                $nextContent = $this->contentContract->nextByOrder($prevOrder);
                $nextOrder = $nextContent ? (float)$nextContent->order : 0;
            } else if ($prevOrder === null && $nextOrder !== null) {
                $prevContent = $this->contentContract->prevByOrder($nextOrder);
                $prevOrder = $prevContent ? (float) $prevContent->order : 0.0;
            }

            $orderableContents = $this->contentContract->orderable($reorderContentIds);

            $totalContent = count($reorderContentIds);
            $start = $prevOrder;
            $end = $nextOrder ?? $start + $totalContent * $this->gap;

            $step = ($end - $start) / ($totalContent + 1);

            if ($step <= 0) {
                $step = 1;
            }

            foreach ($reorderContentIds as $index => $id) {
                $content = $orderableContents->get($id);
                if ($content) {
                    $newOrder = $start + ($index + 1) * $step;
                    $this->contentContract->updateOrder($newOrder, $content);
                }
            }

            return $this->apiResponse->success(
                message: 'Content reordered successfully.',
                code: 'reordered'
            );
        });
    }
}

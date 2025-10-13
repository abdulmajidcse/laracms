<?php

namespace App\Actions;

use App\Contracts\ContentRepositoryContract;

class ContentOrderAction
{
    protected int $gap = 5;

    public function __construct(private ContentRepositoryContract $contentRepository) {}

    public function newOrder(): float
    {
        $max = $this->contentRepository->maxOrder();

        if ($max === null) {
            return $this->gap;
        }

        return (float)$max + $this->gap;
    }
}

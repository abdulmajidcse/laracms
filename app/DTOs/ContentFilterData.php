<?php

namespace App\DTOs;

use App\Enums\ContentStatus;
use App\Enums\ContentType;

readonly class ContentFilterData
{
    public ?string $search;
    public string $sortBy;
    public string $sortDir;
    public ?ContentType $type;
    public ?ContentStatus $status;

    public function __construct(array $data)
    {
        $this->search = $data['search'] ?? null;
        $this->sortBy = $data['sort_by'] ?? 'order';
        $this->sortDir = $data['sort_dir'] ?? 'asc';
        $this->type = isset($data['type']) ? ContentType::tryFrom($data['type']) : null;
        $this->status = isset($data['status']) ? ContentStatus::tryFrom($data['status']) : null;
    }
}

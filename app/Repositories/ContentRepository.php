<?php

namespace App\Repositories;

use App\Models\Content;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
use App\Contracts\ContentRepositoryContract;
use Illuminate\Pagination\LengthAwarePaginator;

class ContentRepository implements ContentRepositoryContract
{
    private function query(): Builder
    {
        return Content::query();
    }

    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return $this->query()
            ->with('createdBy', 'updatedBy')
            ->oldest('order')
            ->paginate($perPage);
    }

    public function findById(int $id): ?Content
    {
        return $this->query()
            ->with('createdBy', 'updatedBy')
            ->where('id', $id)
            ->first();
    }

    public function maxOrder(): ?float
    {
        return $this->query()->max('order');
    }

    public function createOrUpdate(array $data, ?Content $content = null): Content
    {
        if ($content) {
            $content->update($data);
        } else {
            $content = Content::create($data);
        }

        return $content->load('createdBy', 'updatedBy');
    }

    public function delete(int $id): bool
    {
        return $this->query()
            ->where('id', $id)
            ->delete();
    }
}

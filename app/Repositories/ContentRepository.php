<?php

namespace App\Repositories;

use App\Models\Content;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
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
            ->find($id);
    }

    public function findOrFail(int $id): Content
    {
        return $this->query()
            ->findOrFail($id);
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

    public function prevByOrder(float $order): ?Content
    {
        return $this->query()
            ->where('order', '<', $order)
            ->latest('order')
            ->first();
    }

    public function nextByOrder(float $order): ?Content
    {
        return $this->query()
            ->where('order', '>', $order)
            ->oldest('order')
            ->first();;
    }

    public function orderable(array $ids): Collection
    {
        return $this->query()
            ->whereIn('id', $ids)
            ->lockForUpdate()
            ->get()
            ->keyBy('id');
    }

    public function updateOrder(float $order, Content $content): Content
    {
        $content->order = $order;
        $content->updated_by = Auth::id();
        $content->save();

        return $content;
    }
}

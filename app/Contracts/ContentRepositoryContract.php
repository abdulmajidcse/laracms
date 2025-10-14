<?php

namespace App\Contracts;

use App\Models\Content;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface ContentRepositoryContract
{
    public function paginate(int $perPage = 10): LengthAwarePaginator;

    public function findById(int $id): ?Content;

    public function findOrFail(int $id): Content;

    public function maxOrder(): ?float;

    public function createOrUpdate(array $data, ?Content $content = null): Content;

    public function delete(int $id): bool;

    public function prevByOrder(float $order): ?Content;

    public function nextByOrder(float $order): ?Content;

    public function orderable(array $ids): ?Collection;

    public function updateOrder(float $order, Content $content): Content;
}

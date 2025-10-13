<?php

namespace App\Contracts;

use App\Models\Content;
use Illuminate\Pagination\LengthAwarePaginator;

interface ContentRepositoryContract
{
    public function paginate(int $perPage = 10): LengthAwarePaginator;

    public function findById(int $id): ?Content;

    public function maxOrder(): ?float;

    public function createOrUpdate(array $data, ?Content $content = null): Content;

    public function delete(int $id): bool;
}

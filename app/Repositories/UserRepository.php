<?php

namespace App\Repositories;

use App\Contracts\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class UserRepository implements UserRepositoryInterface
{
    private function query(): Builder
    {
        return User::query();
    }

    public function findByEmail(string $email): ?User
    {
        return $this->query()
            ->where('email', $email)
            ->first();
    }
}

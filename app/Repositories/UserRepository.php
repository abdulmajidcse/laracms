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

    public function updateProfile(User $user, array $data): void
    {
        $user->fill($data);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();
    }
}

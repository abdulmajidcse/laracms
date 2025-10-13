<?php

namespace App\Contracts;

use App\Models\User;

interface UserRepositoryContract
{
    public function findByEmail(string $email): ?User;

    public function updateProfile(User $user, array $data): void;
}

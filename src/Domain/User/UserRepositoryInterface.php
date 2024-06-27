<?php

namespace App\Domain\User;

use App\Domain\User\ValueObject\UserId;

interface UserRepositoryInterface
{
    public function generateId(): UserId;

    public function store(UserEntity $user): UserId;
}

<?php

declare(strict_types=1);

namespace App\Application\User;

use App\Domain\User\ValueObject\UserId;

interface UserFinderInterface
{
    public function getById(UserId $id): UserModel;

    /**
     * @return array<UserModel>
     */
    public function getAll(): array;
}

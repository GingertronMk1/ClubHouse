<?php

declare(strict_types=1);

namespace App\Application\User;

use App\Application\User;
use App\Domain\User\ValueObject\UserId;
use Generator;

interface UserFinderInterface
{
    public function getById(UserId $id): User;

    /**
     * @return array<User>
     */
    public function getAll(): array;
}

<?php

declare(strict_types=1);

namespace App\Application\Person;

use App\Application\User;
use App\Domain\Common\ValueObject\DateTime;
use App\Domain\Person\ValueObject\PersonId;

class Person
{
    public function __construct(
        public readonly PersonId $id,
        public readonly string $name,
        public readonly ?User $user,
        public readonly DateTime $createdAt,
        public readonly DateTime $updatedAt,
        public readonly ?DateTime $deletedAt,
    ) {}
}

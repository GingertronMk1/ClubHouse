<?php

declare(strict_types=1);

namespace App\Domain\Person;

use App\Domain\Person\ValueObject\PersonId;
use App\Domain\User\ValueObject\UserId;

class Person
{
    public function __construct(
        public readonly PersonId $id,
        public readonly string $name,
        public readonly ?UserId $userId
    ) {}
}

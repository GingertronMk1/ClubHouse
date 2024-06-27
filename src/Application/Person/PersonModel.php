<?php

declare(strict_types=1);

namespace App\Application\Person;

use App\Application\User\UserModel;
use App\Domain\Common\ValueObject\DateTime;
use App\Domain\Person\ValueObject\PersonId;

class PersonModel
{
    public function __construct(
        public readonly PersonId $id,
        public readonly string $name,
        public readonly ?UserModel $user,
        public readonly DateTime $createdAt,
        public readonly DateTime $updatedAt,
        public readonly ?DateTime $deletedAt,
    ) {}
}

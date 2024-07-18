<?php

declare(strict_types=1);

namespace App\Application\Person;

use App\Application\User\UserModel;
use App\Domain\Common\ValueObject\DateTime;
use App\Domain\Person\ValueObject\PersonId;

class PersonModel implements \JsonSerializable
{
    public function __construct(
        public readonly PersonId $id,
        public readonly string $name,
        public readonly ?UserModel $user,
        public readonly DateTime $createdAt,
        public readonly DateTime $updatedAt,
        public readonly ?DateTime $deletedAt,
    ) {
    }

    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'user' => $this->user,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
            'deletedAt' => $this->deletedAt,
        ];
    }
}

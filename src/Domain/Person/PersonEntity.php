<?php

declare(strict_types=1);

namespace App\Domain\Person;

use App\Domain\Common\AbstractMappedEntity;
use App\Domain\Person\ValueObject\PersonId;
use App\Domain\User\ValueObject\UserId;

class PersonEntity extends AbstractMappedEntity
{
    public function __construct(
        public readonly PersonId $id,
        public readonly string $name,
        public readonly ?UserId $userId
    ) {
    }

    public function getMappedData(array $externalServices = []): array
    {
        return [
            'id' => (string) $this->id,
            'name' => $this->name,
            'user_id' => $this->userId ? (string) $this->userId : null,
        ];
    }
}

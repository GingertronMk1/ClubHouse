<?php

declare(strict_types=1);

namespace App\Domain\Team;

use App\Domain\Common\AbstractMappedEntity;
use App\Domain\Person\ValueObject\PersonId;
use App\Domain\Team\ValueObject\TeamId;

class TeamEntity extends AbstractMappedEntity
{
    /**
     * @param array<PersonId> $peopleIds
     */
    public function __construct(
        public readonly TeamId $id,
        public readonly string $name,
        public readonly string $description,
        public readonly array $peopleIds
    ) {
    }

    public function getMappedData(array $externalServices = []): array
    {
        return [
            'id' => (string) $this->id,
            'name' => $this->name,
            'description' => $this->description,
        ];
    }
}

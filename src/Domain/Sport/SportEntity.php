<?php

declare(strict_types=1);

namespace App\Domain\Sport;

use App\Domain\Common\AbstractMappedEntity;
use App\Domain\Sport\ValueObject\SportId;

class SportEntity extends AbstractMappedEntity
{
    public function __construct(
        public readonly SportId $id,
        public readonly string $name,
        public readonly string $description
    ) {}

    public function getMappedData(array $externalServices = []): array
    {
        return [
            'id' => (string) $this->id,
            'name' => $this->name,
            'description' => $this->description
        ];
    }
}

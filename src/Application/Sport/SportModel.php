<?php

declare(strict_types=1);

namespace App\Application\Sport;

use App\Domain\Common\ValueObject\DateTime;
use App\Domain\Sport\ValueObject\SportId;

class SportModel implements \JsonSerializable
{
    public function __construct(
        public readonly SportId $id,
        public readonly string $name,
        public readonly ?string $description,
        public readonly DateTime $createdAt,
        public readonly DateTime $updatedAt,
        public readonly ?DateTime $deletedAt,
    ) {
    }

    public function jsonSerialize(): mixed
    {
        return [
            'id' => (string) $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'createdAt' => (string) $this->createdAt,
            'updatedAt' => (string) $this->updatedAt,
            'deletedAt' => (string) $this->deletedAt,
        ];
    }
}

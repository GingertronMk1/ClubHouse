<?php

declare(strict_types=1);

namespace App\Application\Sport;

use App\Application\Common\AbstractMappedModel;
use App\Domain\Common\ValueObject\DateTime;
use App\Domain\Sport\ValueObject\SportId;

class SportModel extends AbstractMappedModel
{
    public function __construct(
        public readonly SportId $id,
        public readonly string $name,
        public readonly string $description,
        public readonly DateTime $createdAt,
        public readonly DateTime $updatedAt,
        public readonly ?DateTime $deletedAt,
    ) {}

    public static function createFromRow(array $row, array $externalServices = []): self
    {
        return new self(
            SportId::fromString($row['id']),
            $row['name'],
            $row['description'],
            DateTime::fromString($row['created_at']),
            DateTime::fromString($row['updated_at']),
            isset($row['deleted_at']) ? DateTime::fromString($row['deleted_at']) : null
        );
    }
}

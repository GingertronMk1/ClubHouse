<?php

declare(strict_types=1);

namespace App\Application\Team;

use App\Application\Common\AbstractMappedModel;
use App\Application\Person\PersonFinderInterface;
use App\Application\Sport\SportFinderInterface;
use App\Application\Sport\SportModel;
use App\Domain\Common\ValueObject\DateTime;
use App\Domain\Sport\ValueObject\SportId;
use App\Domain\Team\ValueObject\TeamId;
use JsonSerializable;

class TeamModel implements JsonSerializable
{
    public function __construct(
        public readonly TeamId $id,
        public readonly string $name,
        public readonly string $description,
        public readonly SportModel $sport,
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
            'sport' => $this->sport,
            'createdAt' => (string) $this->createdAt,
            'updatedAt' => (string) $this->updatedAt,
            'deletedAt' => (string) $this->deletedAt,
        ];
    }
}

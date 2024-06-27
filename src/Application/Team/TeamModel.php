<?php

declare(strict_types=1);

namespace App\Application\Team;

use App\Application\Person\PersonModel;
use App\Domain\Common\ValueObject\DateTime;
use App\Domain\Team\ValueObject\TeamId;

class TeamModel
{
    /**
     * @param array<PersonModel> $people
     */
    public function __construct(
        public readonly TeamId $id,
        public readonly string $name,
        public readonly string $description,
        public readonly array $people,
        public readonly DateTime $createdAt,
        public readonly DateTime $updatedAt,
        public readonly ?DateTime $deletedAt,
    ) {}
}

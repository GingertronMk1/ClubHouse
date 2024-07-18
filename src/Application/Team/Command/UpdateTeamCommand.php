<?php

declare(strict_types=1);

namespace App\Application\Team\Command;

use App\Application\Person\PersonModel;
use App\Application\Team\TeamModel;
use App\Domain\Sport\ValueObject\SportId;
use App\Domain\Team\ValueObject\TeamId;

class UpdateTeamCommand
{
    /**
     * @param array<PersonModel> $people
     */
    private function __construct(
        public TeamId $id,
        public string $name,
        public string $description,
        public array $people,
        public SportId $sportId
    ) {
    }

    public static function fromTeam(TeamModel $team): self
    {
        return new self(
            $team->id,
            $team->name,
            $team->description,
            $team->people,
            $team->sport->id
        );
    }
}

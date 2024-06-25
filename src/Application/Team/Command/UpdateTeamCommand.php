<?php

declare(strict_types=1);

namespace App\Application\Team\Command;

use App\Application\Person\Person;
use App\Application\Team\Team;
use App\Domain\Team\ValueObject\TeamId;

class UpdateTeamCommand
{
    /**
     * @param array<Person> $people
     */
    private function __construct(
        public TeamId $id,
        public string $name,
        public string $description,
        public array $people,
    ) {}

    public static function fromTeam(Team $team): self
    {
        return new self(
            $team->id,
            $team->name,
            $team->description,
            $team->people
        );
    }
}

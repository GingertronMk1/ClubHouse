<?php

declare(strict_types=1);

namespace App\Application\Team\CommandHandler;

use App\Application\Person\Person;
use App\Application\Team\Command\UpdateTeamCommand;
use App\Domain\Team\Team;
use App\Domain\Team\TeamRepositoryInterface;
use App\Domain\Team\ValueObject\TeamId;

class UpdateTeamCommandHandler
{
    public function __construct(
        private readonly TeamRepositoryInterface $teamRepository
    ) {}

    public function handle(UpdateTeamCommand $command): TeamId
    {
        $team = new Team(
            $command->id,
            $command->name,
            $command->description,
            array_map(fn(Person $person) => $person->id, $command->people)
        );

        return $this->teamRepository->store($team);
    }
}

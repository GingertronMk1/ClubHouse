<?php

declare(strict_types=1);

namespace App\Application\Team\CommandHandler;

use App\Application\Team\Command\CreateTeamCommand;
use App\Domain\Team\TeamEntity;
use App\Domain\Team\TeamRepositoryInterface;
use App\Domain\Team\ValueObject\TeamId;

class CreateTeamCommandHandler
{
    public function __construct(
        private readonly TeamRepositoryInterface $teamRepository
    ) {
    }

    public function handle(CreateTeamCommand $command): TeamId
    {
        $team = new TeamEntity(
            $this->teamRepository->generateId(),
            $command->name,
            $command->description,
            $command->people,
            $command->sport
        );

        return $this->teamRepository->store($team);
    }
}

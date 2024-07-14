<?php

declare(strict_types=1);

namespace App\Application\Team\CommandHandler;

use App\Application\Person\PersonModel;
use App\Application\Team\Command\UpdateTeamCommand;
use App\Domain\Team\TeamEntity;
use App\Domain\Team\TeamRepositoryInterface;
use App\Domain\Team\ValueObject\TeamId;

class UpdateTeamCommandHandler
{
    public function __construct(
        private readonly TeamRepositoryInterface $teamRepository
    ) {
    }

    public function handle(UpdateTeamCommand $command): TeamId
    {
        $team = new TeamEntity(
            $command->id,
            $command->name,
            $command->description,
            array_map(fn (PersonModel $person) => $person->id, $command->people)
        );

        return $this->teamRepository->store($team);
    }
}

<?php

declare(strict_types=1);

namespace App\Application\Match\CommandHandler;

use App\Application\Match\Command\CreateMatchCommand;
use App\Domain\Common\ValueObject\DateTime;
use App\Domain\Match\MatchEntity;
use App\Domain\Match\MatchRepositoryInterface;
use App\Domain\Match\ValueObject\MatchId;

class CreateMatchCommandHandler
{
    public function __construct(
        private readonly MatchRepositoryInterface $matchRepository
    ) {
    }

    public function handle(CreateMatchCommand $command): MatchId
    {
        $matchEntity = new MatchEntity(
            $this->matchRepository->generateId(),
            $command->name,
            $command->details,
            $command->start ? DateTime::fromDateTimeInterface($command->start) : null,
            $command->team1?->id,
            $command->team2?->id,
            $command->sport?->id,
        );

        return $this->matchRepository->store($matchEntity);
    }
}

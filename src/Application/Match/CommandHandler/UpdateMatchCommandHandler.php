<?php

declare(strict_types=1);

namespace App\Application\Match\CommandHandler;

use App\Application\Match\Command\UpdateMatchCommand;
use App\Domain\Common\ValueObject\DateTime;
use App\Domain\Match\MatchEntity;
use App\Domain\Match\MatchRepositoryInterface;
use App\Domain\Match\ValueObject\MatchId;

class UpdateMatchCommandHandler
{
    public function __construct(
        private readonly MatchRepositoryInterface $matchRepository
    ) {}

    public function handle(UpdateMatchCommand $command): MatchId
    {
        $matchEntity = new MatchEntity(
            $command->id,
            $command->name,
            $command->details,
            $command->start ? DateTime::fromDateTimeInterface($command->start) : null,
            $command->team1?->id,
            $command->team2?->id,
            $command->sport?->id
        );

        return $this->matchRepository->store($matchEntity);
    }
}

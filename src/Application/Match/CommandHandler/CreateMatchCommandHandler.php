<?php

declare(strict_types=1);

namespace App\Application\Match\CommandHandler;

use App\Application\Match\Command\CreateMatchCommand;
use App\Domain\Match\MatchRepositoryInterface;
use App\Domain\Match\ValueObject\MatchId;

class CreateMatchCommandHandler
{
    public function __construct(
        private readonly MatchRepositoryInterface $matchRepository
    ) {}

    public function handle(CreateMatchCommand $command): MatchId
    {
        return $this->matchRepository->generateId();
    }
}

<?php

declare(strict_types=1);

namespace App\Application\Sport\CommandHandler;

use App\Application\Sport\Command\UpdateSportCommand;
use App\Domain\Sport\SportEntity;
use App\Domain\Sport\SportRepositoryInterface;
use App\Domain\Sport\ValueObject\SportId;

class UpdateSportCommandHandler
{
    public function __construct(
        private readonly SportRepositoryInterface $sportRepository
    ) {
    }

    public function handle(UpdateSportCommand $command): SportId
    {
        $sport = new SportEntity(
            $command->id,
            $command->name,
            $command->description
        );

        return $this->sportRepository->store($sport);
    }
}

<?php

declare(strict_types=1);

namespace App\Application\Sport\CommandHandler;

use App\Application\Sport\Command\CreateSportCommand;
use App\Domain\Sport\SportEntity;
use App\Domain\Sport\SportRepositoryInterface;
use App\Domain\Sport\ValueObject\SportId;

class CreateSportCommandHandler
{
    public function __construct(
        private readonly SportRepositoryInterface $sportRepository
    ) {}

    public function handle(CreateSportCommand $command): SportId
    {
        $id = $this->sportRepository->generateId();
        $sportEntity = new SportEntity(
            $id,
            $command->name,
            $command->description
        );

        return $this->sportRepository->store($sportEntity);
    }
}

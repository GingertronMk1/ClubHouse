<?php

declare(strict_types=1);

namespace App\Application\Person\CommandHandler;

use App\Application\Person\Command\CreatePersonCommand;
use App\Domain\Person\PersonEntity;
use App\Domain\Person\PersonRepositoryInterface;
use App\Domain\Person\ValueObject\PersonId;

class CreatePersonCommandHandler
{
    public function __construct(
        private readonly PersonRepositoryInterface $personRepository
    ) {
    }

    public function handle(CreatePersonCommand $command): PersonId
    {
        $person = new PersonEntity(
            $this->personRepository->generateId(),
            $command->name,
            $command->user?->id
        );

        return $this->personRepository->store($person);
    }
}

<?php

declare(strict_types=1);

namespace App\Application\Person\CommandHandler;

use App\Application\Person\Command\UpdatePersonCommand;
use App\Domain\Person\Person;
use App\Domain\Person\PersonRepositoryInterface;
use App\Domain\Person\ValueObject\PersonId;

class UpdatePersonCommandHandler
{
    public function __construct(
        private readonly PersonRepositoryInterface $personRepository
    ) {}

    public function handle(UpdatePersonCommand $command): PersonId
    {
        $person = new Person(
            $command->id,
            $command->name,
            $command->user?->id
        );

        return $this->personRepository->store($person);
    }
}

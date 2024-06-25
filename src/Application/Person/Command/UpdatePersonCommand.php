<?php

declare(strict_types=1);

namespace App\Application\Person\Command;

use App\Application\Person\Person;
use App\Application\User;
use App\Domain\Person\ValueObject\PersonId;

class UpdatePersonCommand
{
    private function __construct(
        public PersonId $id,
        public string $name,
        public ?User $user
    ) {}

    public static function fromPerson(Person $person): self
    {
        return new self(
            $person->id,
            $person->name,
            $person->user,
        );
    }
}

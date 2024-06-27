<?php

declare(strict_types=1);

namespace App\Application\Person\Command;

use App\Application\Person\PersonModel;
use App\Application\User\UserModel;
use App\Domain\Person\ValueObject\PersonId;

class UpdatePersonCommand
{
    private function __construct(
        public PersonId $id,
        public string $name,
        public ?UserModel $user
    ) {}

    public static function fromPerson(PersonModel $person): self
    {
        return new self(
            $person->id,
            $person->name,
            $person->user,
        );
    }
}

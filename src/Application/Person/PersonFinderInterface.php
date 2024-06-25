<?php

declare(strict_types=1);

namespace App\Application\Person;

use App\Domain\Person\ValueObject\PersonId;

interface PersonFinderInterface
{
    public function getById(PersonId $id): Person;

    /**
     * @return array<Person>
     */
    public function getAll(): array;
}

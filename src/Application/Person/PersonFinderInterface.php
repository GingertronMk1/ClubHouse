<?php

declare(strict_types=1);

namespace App\Application\Person;

use App\Domain\Person\ValueObject\PersonId;

interface PersonFinderInterface
{
    public function getById(PersonId $id): PersonModel;

    /**
     * @param array<PersonId> $peopleIds
     *
     * @return array<PersonModel>
     */
    public function getAll(array $peopleIds = []): array;
}

<?php

declare(strict_types=1);

namespace App\Application\Person;

use App\Domain\Person\ValueObject\PersonId;
use App\Domain\Team\ValueObject\TeamId;

interface PersonFinderInterface
{
    public function getById(PersonId $id): PersonModel;

    /**
     * @param array<PersonId> $peopleIds
     *
     * @return array<PersonModel>
     */
    public function getAll(array $peopleIds = []): array;

    /**
     * @return array<PersonModel>
     */
    public function getForTeam(TeamId $teamId): array;
}

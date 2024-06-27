<?php

declare(strict_types=1);

namespace App\Domain\Person;

use App\Domain\Person\ValueObject\PersonId;

interface PersonRepositoryInterface
{
    public function generateId(): PersonId;

    public function store(PersonEntity $person): PersonId;
}

<?php

declare(strict_types=1);

namespace App\Domain\MatchPerson;

use App\Domain\Match\ValueObject\MatchId;

interface MatchPersonRepositoryInterface
{
    public function store(MatchPersonEntity $entity): MatchId;
}

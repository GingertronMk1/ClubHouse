<?php

declare(strict_types=1);

namespace App\Domain\Match;

use App\Domain\Match\ValueObject\MatchId;

interface MatchRepositoryInterface
{
    public function generateId(): MatchId;

    public function store(MatchEntity $match): MatchId;
}

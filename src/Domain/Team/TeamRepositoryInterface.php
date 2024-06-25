<?php

declare(strict_types=1);

namespace App\Domain\Team;

use App\Domain\Team\ValueObject\TeamId;

interface TeamRepositoryInterface
{
    public function generateId(): TeamId;

    public function store(Team $team): TeamId;
}

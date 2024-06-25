<?php

declare(strict_types=1);

namespace App\Application\Team;

use App\Domain\Team\ValueObject\TeamId;

interface TeamFinderInterface
{
    public function getById(TeamId $id): Team;
    /**
     * @return array<Team>
     */
    public function getAll(): array;
}

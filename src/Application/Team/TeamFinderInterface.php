<?php

declare(strict_types=1);

namespace App\Application\Team;

use App\Domain\Team\ValueObject\TeamId;

interface TeamFinderInterface
{
    public function getById(TeamId $id): Team;

    /**
     * @param array<TeamId> $teamIds
     *
     * @return array<Team>
     */
    public function getAll(array $teamIds = []): array;
}

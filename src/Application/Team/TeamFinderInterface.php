<?php

declare(strict_types=1);

namespace App\Application\Team;

use App\Domain\Team\ValueObject\TeamId;

interface TeamFinderInterface
{
    public function getById(TeamId $id): TeamModel;

    /**
     * @param array<TeamId> $teamIds
     *
     * @return array<TeamModel>
     */
    public function getAll(array $teamIds = []): array;
}

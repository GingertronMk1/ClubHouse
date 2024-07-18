<?php

declare(strict_types=1);

namespace App\Application\Team;

use App\Domain\Sport\ValueObject\SportId;
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

    /**
     * @return array<TeamModel>
     */
    public function getForSport(SportId $id): array;
}

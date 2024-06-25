<?php

declare(strict_types=1);

namespace App\Infrastructure\Team;

use App\Application\Common\Service\ClockInterface;
use App\Domain\Team\Team;
use App\Domain\Team\TeamRepositoryInterface;
use App\Domain\Team\ValueObject\TeamId;
use App\Infrastructure\Common\AbstractDbalRepository;
use Doctrine\DBAL\Connection;

class DbalTeamRepository extends AbstractDbalRepository implements TeamRepositoryInterface
{
    public function __construct(
        private readonly Connection $connection,
        private readonly ClockInterface $clock
    ) {}

    public function generateId(): TeamId
    {
        return TeamId::generate();
    }

    public function store(Team $team): TeamId
    {
        $storeTeam = $this->storeMappedData(
            $team,
            $this->connection,
            'teams',
            $this->clock
        );

        if (1 !== $storeTeam) {
            throw new \Exception("Stored {$storeTeam} people, when we should have stored just one!");
        }

        return $team->id;
    }
}

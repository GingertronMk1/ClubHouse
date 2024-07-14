<?php

declare(strict_types=1);

namespace App\Infrastructure\Match;

use App\Application\Common\Service\ClockInterface;
use App\Domain\Match\MatchEntity;
use App\Domain\Match\MatchRepositoryInterface;
use App\Domain\Match\ValueObject\MatchId;
use App\Infrastructure\Common\AbstractDbalRepository;
use Doctrine\DBAL\Connection;

class DbalMatchRepository extends AbstractDbalRepository implements MatchRepositoryInterface
{
    public function __construct(
        private readonly Connection $connection,
        private readonly ClockInterface $clockInterface,
    ) {
    }

    public function generateId(): MatchId
    {
        return MatchId::generate();
    }

    public function store(MatchEntity $match): MatchId
    {
        $this->storeMappedData(
            $match,
            $this->connection,
            'matches',
            $this->clockInterface,
        );

        return $match->id;
    }
}

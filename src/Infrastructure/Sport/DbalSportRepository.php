<?php

declare(strict_types=1);

namespace App\Infrastructure\Sport;

use App\Application\Common\Service\ClockInterface;
use App\Domain\Sport\SportEntity;
use App\Domain\Sport\SportRepositoryInterface;
use App\Domain\Sport\ValueObject\SportId;
use App\Infrastructure\Common\AbstractDbalRepository;
use Doctrine\DBAL\Connection;

class DbalSportRepository extends AbstractDbalRepository implements SportRepositoryInterface
{
    public function __construct(
        private readonly Connection $connection,
        private readonly ClockInterface $clock,
    ) {
    }

    public function generateId(): SportId
    {
        return SportId::generate();
    }

    public function store(SportEntity $sport): SportId
    {
        $storeSport = $this->storeMappedData(
            $sport,
            $this->connection,
            'sports',
            $this->clock
        );
        if (1 !== $storeSport) {
            throw new \Exception("Stored {$storeSport} sports, when we should have stored just one!");
        }

        return $sport->id;
    }
}

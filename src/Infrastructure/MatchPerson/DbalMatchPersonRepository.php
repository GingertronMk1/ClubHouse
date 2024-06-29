<?php

declare(strict_types=1);

namespace App\Infrastructure\MatchPerson;

use App\Application\Common\Service\ClockInterface;
use App\Domain\Match\ValueObject\MatchId;
use App\Domain\MatchPerson\MatchPersonEntity;
use App\Domain\MatchPerson\MatchPersonRepositoryInterface;
use App\Infrastructure\Common\AbstractDbalRepository;
use Doctrine\DBAL\Connection;

class DbalMatchPersonRepository extends AbstractDbalRepository implements MatchPersonRepositoryInterface
{
    public function __construct(
        private readonly Connection $connection,
        private readonly ClockInterface $clockInterface
    ) {}

    public function store(MatchPersonEntity $entity): MatchId
    {
        $rows = $this->storeMappedData(
            $entity,
            $this->connection,
            'match_people',
            $this->clockInterface,
            ['match_id', 'person_id'],
        );

        if (1 !== $rows) {
            throw new \Exception('Error storing match person');
        }

        return $entity->matchId;
    }
}

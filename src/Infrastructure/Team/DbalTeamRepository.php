<?php

declare(strict_types=1);

namespace App\Infrastructure\Team;

use App\Application\Common\Service\ClockInterface;
use App\Domain\Person\ValueObject\PersonId;
use App\Domain\Team\Team;
use App\Domain\Team\TeamRepositoryInterface;
use App\Domain\Team\ValueObject\TeamId;
use App\Infrastructure\Common\AbstractDbalRepository;
use Doctrine\DBAL\Connection;
use Psr\Log\LoggerInterface;

class DbalTeamRepository extends AbstractDbalRepository implements TeamRepositoryInterface
{
    public function __construct(
        private readonly Connection $connection,
        private readonly ClockInterface $clock,
        private readonly LoggerInterface $logger,
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

        $removeQuery = $this->connection->createQueryBuilder();
        $removeQuery
            ->delete('team_people')
        ;

        foreach ($team->peopleIds as $n => $personId) {
            match ($this->associateUser($team->id, $personId)) {
                -1 => $this->logger->error("Person {$personId} is already associated with team {$team->id}"),
                0 => $this->logger->error("There was an error associating person {$personId} with team {$team->id}"),
                default => null
            };

            $removeQuery
                ->andWhere("person_id <> :person_id_{$n}")
                ->setParameter("person_id_{$n}", (string) $personId)
            ;
        }

        $removeQuery->executeStatement();

        return $team->id;
    }

    private function associateUser(TeamId $teamId, PersonId $personId): int
    {
        $checkQuery = $this->connection->createQueryBuilder();
        $checkQuery
            ->select('*')
            ->from('team_people')
            ->where('person_id = :person_id', 'team_id = :team_id')
            ->setParameters([
                'person_id' => (string) $personId,
                'team_id' => (string) $teamId,
            ])
        ;
        if (empty($checkQuery->fetchAllAssociative())) {
            $createQuery = $this->connection->createQueryBuilder();
            $createQuery
                ->insert('team_people')
                ->values([
                    'person_id' => ':person_id',
                    'team_id' => ':team_id',
                ])
                ->setParameters([
                    'person_id' => (string) $personId,
                    'team_id' => (string) $teamId,
                ])
            ;

            return (int) $createQuery->executeStatement();
        }

        return -1;
    }
}

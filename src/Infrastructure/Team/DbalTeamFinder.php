<?php

declare(strict_types=1);

namespace App\Infrastructure\Team;

use App\Application\Person\PersonFinderInterface;
use App\Application\Team\Team;
use App\Application\Team\TeamFinderInterface;
use App\Domain\Common\ValueObject\DateTime;
use App\Domain\Person\ValueObject\PersonId;
use App\Domain\Team\ValueObject\TeamId;
use Doctrine\DBAL\Connection;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DbalTeamFinder implements TeamFinderInterface
{
    private const TABLE_NAME = 'teams';

    public function __construct(
        private readonly Connection $connection,
        private readonly LoggerInterface $logger,
        private readonly PersonFinderInterface $personFinder
    ) {}

    public function getById(TeamId $id): Team
    {
        $query = $this->connection->createQueryBuilder();
        $query
            ->select('*')
            ->from(self::TABLE_NAME)
            ->where('id = :id')
            ->setParameter('id', (string) $id)
        ;

        $result = $query->fetchAssociative();

        if (!$result) {
            throw new NotFoundHttpException('User not found');
        }

        return $this->createFromRow($result);
    }

    public function getAll(array $teamIds = []): array
    {
        $query = $this->connection->createQueryBuilder();
        $query
            ->select('*')
            ->from(self::TABLE_NAME)
            ->orderBy('id')
        ;

        if (!empty($teamIds)) {
            $expr = $this->connection->createExpressionBuilder();
            $query->where($expr->in('id', array_map(fn (TeamId $id) => (string) $id, $teamIds)));
        }

        $result = $query->fetchAllAssociative();

        $returnVal = [];

        foreach ($result as $row) {
            try {
                $returnVal[] = $this->createFromRow($row);
            } catch (\Throwable $e) {
                $this->logger->error($e->getMessage());
            }
        }

        return $returnVal;
    }

    /**
     * @param array<string, mixed> $row
     */
    private function createFromRow(array $row): Team
    {
        $deletedAt = null;
        if (isset($row['deleted_at'])) {
            $deletedAt = DateTime::fromString($row['deleted_at']);
        }

        $peopleQuery = $this->connection->createQueryBuilder();
        $peopleQuery
            ->select('person_id')
            ->from('team_people')
            ->where('team_id = :team_id')
            ->setParameter('team_id', $row['id'])
        ;
        $peopleIds = $peopleQuery->fetchFirstColumn();

        $peopleIds = array_map(fn (string $personId) => PersonId::fromString($personId), $peopleIds);

        $teamPeople = [];

        if (!empty($peopleIds)) {
            $teamPeople = $this->personFinder->getAll($peopleIds);
        }

        return new Team(
            TeamId::fromString($row['id']),
            $row['name'],
            $row['description'],
            $teamPeople,
            DateTime::fromString($row['created_at']),
            DateTime::fromString($row['updated_at']),
            $deletedAt
        );
    }
}

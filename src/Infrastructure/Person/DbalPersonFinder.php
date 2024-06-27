<?php

declare(strict_types=1);

namespace App\Infrastructure\Person;

use App\Application\Person\PersonFinderInterface;
use App\Application\Person\PersonModel;
use App\Application\User\UserFinderInterface;
use App\Domain\Person\ValueObject\PersonId;
use App\Domain\Team\ValueObject\TeamId;
use Doctrine\DBAL\Connection;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DbalPersonFinder implements PersonFinderInterface
{
    private const TABLE_NAME = 'people';

    public function __construct(
        private readonly Connection $connection,
        private readonly UserFinderInterface $userFinder,
        private readonly LoggerInterface $logger
    ) {}

    public function getById(PersonId $id): PersonModel
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

    /**
     * @param array<PersonId> $peopleIds
     */
    public function getAll(array $peopleIds = []): array
    {
        $query = $this->connection->createQueryBuilder();
        $query
            ->select('*')
            ->from(self::TABLE_NAME)
            ->orderBy('id')
        ;

        foreach ($peopleIds as $personIdIndex => $personIdValue) {
            $query
                ->orWhere("id = :id{$personIdIndex}")
                ->setParameter("id{$personIdIndex}", (string) $personIdValue)
            ;
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

    public function getForTeam(TeamId $teamId): array
    {
        $peopleQuery = $this->connection->createQueryBuilder();
        $peopleQuery
            ->select('person_id')
            ->from('team_people')
            ->where('team_id = :team_id')
            ->setParameter('team_id', (string) $teamId)
        ;
        $peopleIds = $peopleQuery->fetchFirstColumn();

        $peopleIds = array_map(fn (string $personId) => PersonId::fromString($personId), $peopleIds);

        $teamPeople = [];

        if (!empty($peopleIds)) {
            $teamPeople = $this->getAll($peopleIds);
        }

        return $teamPeople;
    }

    /**
     * @param array<string, mixed> $row
     */
    private function createFromRow(array $row): PersonModel
    {
        return PersonModel::createFromRow($row, [
            UserFinderInterface::class => $this->userFinder,
        ]);
    }
}

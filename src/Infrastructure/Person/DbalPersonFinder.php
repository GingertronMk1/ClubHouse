<?php

declare(strict_types=1);

namespace App\Infrastructure\Person;

use App\Application\Person\PersonFinderInterface;
use App\Application\Person\PersonModel;
use App\Application\User\UserFinderInterface;
use App\Domain\Common\ValueObject\DateTime;
use App\Domain\Person\ValueObject\PersonId;
use App\Domain\Team\ValueObject\TeamId;
use App\Domain\User\ValueObject\UserId;
use Doctrine\DBAL\Connection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DbalPersonFinder implements PersonFinderInterface
{
    private const TABLE_NAME = 'people';

    public function __construct(
        private readonly Connection $connection,
        private readonly UserFinderInterface $userFinder,
    ) {
    }

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

        return array_map(
            fn (array $row) => $this->createFromRow($row),
            $query->fetchAllAssociative()
        );
    }

    public function getForTeam(TeamId $teamId): array
    {
        $peopleQuery = $this->connection->createQueryBuilder();
        $peopleQuery
            ->select(self::TABLE_NAME.'.*')
            ->from(self::TABLE_NAME)
            ->innerJoin(
                'people',
                'team_people',
                'team_people',
                'team_people.person_id = '.self::TABLE_NAME.'.id',
            )
            ->where('team_people.team_id = :team_id')
            ->setParameter('team_id', (string) $teamId)
        ;

        return array_map(
            fn (array $row) => $this->createFromRow($row),
            $peopleQuery->fetchAllAssociative()
        );
    }

    /**
     * @param array<string, mixed> $row
     */
    private function createFromRow(array $row): PersonModel
    {
        return new PersonModel(
            id: PersonId::fromString($row['id']),
            name: $row['name'],
            user: isset($row['user_id']) ? $this->userFinder->getById(UserId::fromString($row['user_id'])) : null,
            createdAt: DateTime::fromString($row['created_at']),
            updatedAt: DateTime::fromString($row['updated_at']),
            deletedAt: isset($row['deleted_at']) ? DateTime::fromString($row['deletedAt']) : null
        );
    }
}

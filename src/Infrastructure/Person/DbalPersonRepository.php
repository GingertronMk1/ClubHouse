<?php

declare(strict_types=1);

namespace App\Infrastructure\Person;

use App\Application\Common\Service\ClockInterface;
use App\Domain\Person\Person;
use App\Domain\Person\PersonRepositoryInterface;
use App\Domain\Person\ValueObject\PersonId;
use Doctrine\DBAL\Connection;

class DbalPersonRepository implements PersonRepositoryInterface
{
    private const TABLE_NAME = 'people';

    public function __construct(
        private readonly Connection $connection,
        private readonly ClockInterface $clock
    ) {}

    public function generateId(): PersonId
    {
        return PersonId::generate();
    }

    public function store(Person $person): PersonId
    {
        $existsQuery = $this->connection->createQueryBuilder();
        $existsQuery
            ->select('*')
            ->from(self::TABLE_NAME)
            ->where('id = :id')
            ->setParameter(
                'id',
                (string) $person->id
            )
        ;

        if ($existsQuery->fetchAssociative()) {
            $updateQuery = $this->connection->createQueryBuilder();
            $updateQuery
                ->update(self::TABLE_NAME)
                ->where('id = :id')
                ->set('name', ':name')
                ->set('user_id', ':user_id')
                ->set('updated_at', ':updated_at')
                ->setParameters([
                    'id' => (string) $person->id,
                    'name' => $person->name,
                    'user_id' => $person->userId ? (string) $person->userId : null,
                    'updated_at' => (string) $this->clock->getTime(),
                ])
            ;
            $updateQuery->executeStatement();
        } else {
            $insertQuery = $this->connection->createQueryBuilder();
            $insertQuery
                ->insert(self::TABLE_NAME)
                ->values([
                    'id' => ':id',
                    'name' => ':name',
                    'user_id' => ':user_id',
                    'created_at' => ':now',
                    'updated_at' => ':now',
                ])
                ->setParameters([
                    'id' => (string) $person->id,
                    'name' => $person->name,
                    'user_id' => $person->userId ? (string) $person->userId : null,
                    'now' => (string) $this->clock->getTime(),
                ])
            ;
            $insertQuery->executeStatement();
        }

        return $person->id;
    }
}

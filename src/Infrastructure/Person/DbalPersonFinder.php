<?php

declare(strict_types=1);

namespace App\Infrastructure\Person;

use App\Application\Person\Person;
use App\Application\Person\PersonFinderInterface;
use App\Application\User\UserFinderInterface;
use App\Domain\Common\ValueObject\DateTime;
use App\Domain\Person\ValueObject\PersonId;
use App\Domain\User\ValueObject\UserId;
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

    public function getById(PersonId $id): Person
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

    public function getAll(): array
    {
        $query = $this->connection->createQueryBuilder();
        $query
            ->select('*')
            ->from(self::TABLE_NAME)
        ;

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
    private function createFromRow(array $row): Person
    {
        $user = null;
        if (isset($row['user_id'])) {
            $user = $this->userFinder->getById(UserId::fromString($row['user_id']));
        }
        $deletedAt = null;
        if (isset($row['deleted_at'])) {
            $deletedAt = DateTime::fromString($row['deleted_at']);
        }

        return new Person(
            PersonId::fromString($row['id']),
            $row['name'],
            $user,
            DateTime::fromString($row['created_at']),
            DateTime::fromString($row['updated_at']),
            $deletedAt
        );
    }
}

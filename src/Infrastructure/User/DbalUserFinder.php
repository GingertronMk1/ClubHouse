<?php

namespace App\Infrastructure\User;

use App\Application\User;
use App\Application\User\UserFinderInterface;
use App\Domain\User\ValueObject\UserId;
use Doctrine\DBAL\Connection;
use Generator;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DbalUserFinder implements UserFinderInterface
{
    private const TABLE_NAME = 'users';

    public function __construct(
        private readonly Connection $connection,
        private readonly LoggerInterface $logger
    )
    {
    }

    public function getById(UserId $id): User
    {
        $query = $this->connection->createQueryBuilder();
        $query
            ->select('*')
            ->from(self::TABLE_NAME)
            ->where('id = :id')
            ->setParameter('id', (string) $id);

        $result = $query->fetchAssociative();

        if (!$result) {
            throw new NotFoundHttpException("User not found");
        }

        return $this->createFromRow($result);
    }

    public function getAll(): array
    {
        $query = $this->connection->createQueryBuilder();
        $query
            ->select('*')
            ->from(self::TABLE_NAME);

        $result = $query->fetchAllAssociative();

        $returnVal = [];

        foreach($result as $row) {
            try {
                $returnVal[] = $this->createFromRow($row);       
            } catch (\Throwable $e) {
                $this->logger->error($e->getMessage());
            }
        }

        return $returnVal;
    }

    private function createFromRow(array $row): User
    {
        if (!(isset($row['id']) && isset($row['email']))) {
            throw new \Exception('Values not set');
        }

        return new User(
            UserId::fromString($row['id']),
            $row['email'],
            []
        );
    }
}

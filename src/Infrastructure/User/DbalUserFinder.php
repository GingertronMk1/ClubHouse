<?php

namespace App\Infrastructure\User;

use App\Application\User\UserFinderInterface;
use App\Application\User\UserModel;
use App\Domain\User\ValueObject\UserId;
use Doctrine\DBAL\Connection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DbalUserFinder implements UserFinderInterface
{
    private const TABLE_NAME = 'users';

    public function __construct(
        private readonly Connection $connection,
    ) {
    }

    public function getById(UserId $id): UserModel
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

        return array_map(
            fn (array $row) => $this->createFromRow($row),
            $query->fetchAllAssociative()
        );
    }

    /**
     * @param array<string, mixed> $row
     */
    private function createFromRow(array $row): UserModel
    {
        return UserModel::createFromRow($row);
    }
}

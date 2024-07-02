<?php

declare(strict_types=1);

namespace App\Infrastructure\Common;

use App\Application\Common\Service\ClockInterface;
use App\Domain\Common\AbstractMappedEntity;
use Doctrine\DBAL\Connection;

abstract class AbstractDbalRepository
{
    /**
     * Store any given mapped entity.
     *
     * @param array<string>|string $idColumn
     * @param array<string, mixed> $externalServices
     */
    protected function storeMappedData(
        AbstractMappedEntity $entity,
        Connection $connection,
        string $tableName,
        ?ClockInterface $clock = null,
        array|string $idColumn = 'id',
        array $externalServices = []
    ): int {
        if (is_string($idColumn)) {
            $idColumn = [$idColumn];
        }

        // Having defined the column that identifies it, we first check to see if the thing exists already.
        // If it does, update, otherwise insert
        $entityMappedData = $entity->getMappedData($externalServices);
        $existsQuery = $connection->createQueryBuilder();
        $existsQuery
            ->select('*')
            ->from($tableName)
        ;
        foreach ($idColumn as $colKey => $col) {
            $existsQuery
                ->andWhere("{$col} = :id{$colKey}")
                ->setParameter("id{$colKey}", $entityMappedData[$col])
            ;
        }

        if ($existsQuery->fetchAssociative()) {
            return $this->update(
                $entity,
                $connection,
                $tableName,
                $clock,
                $idColumn,
                $externalServices
            );
        }

        return $this->create(
            $entity,
            $connection,
            $tableName,
            $clock,
            $externalServices
        );
    }

    /**
     * @param array<string, mixed> $externalServices
     */
    private function create(
        AbstractMappedEntity $entity,
        Connection $connection,
        string $tableName,
        ?ClockInterface $clock = null,
        array $externalServices = [],
    ): int {
        $storeQuery = $connection->createQueryBuilder();
        $storeQuery
            ->insert($tableName)
        ;
        foreach ($entity->getMappedData($externalServices) as $column => $value) {
            $storeQuery
                ->setValue($column, ":{$column}")
                ->setParameter($column, $value)
            ;
        }
        if ($clock) {
            $storeQuery
                ->setValue('created_at', ':now')
                ->setValue('updated_at', ':now')
                ->setParameter('now', (string) $clock->getTime())
            ;
        }

        return (int) $storeQuery->executeStatement();
    }

    /**
     * @param array<string>        $idColumn
     * @param array<string, mixed> $externalServices
     */
    private function update(
        AbstractMappedEntity $entity,
        Connection $connection,
        string $tableName,
        ?ClockInterface $clock = null,
        array $idColumn = ['id'],
        array $externalServices = []
    ): int {
        $entityMappedData = $entity->getMappedData($externalServices);
        $storeQuery = $connection->createQueryBuilder();
        $storeQuery
            ->update($tableName)
        ;
        foreach ($idColumn as $colKey => $col) {
            $storeQuery
                ->andWhere("{$col} = :id{$colKey}")
                ->setParameter("id{$colKey}", $entityMappedData[$col])
            ;
        }

        foreach ($entityMappedData as $column => $value) {
            $storeQuery
                ->set($column, ":{$column}")
                ->setParameter($column, $value)
            ;
        }
        if ($clock) {
            $storeQuery
                ->set('updated_at', ':now')
                ->setParameter('now', (string) $clock->getTime())
            ;
        }

        return (int) $storeQuery->executeStatement();
    }
}

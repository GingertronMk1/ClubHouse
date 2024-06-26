<?php

declare(strict_types=1);

namespace App\Infrastructure\Common;

use App\Application\Common\Service\ClockInterface;
use App\Domain\Common\AbstractMappedEntity;
use Doctrine\DBAL\Connection;

abstract class AbstractDbalRepository
{
    /**
     * Store any given mapped entity
     * 
     * @param string|array<string> $idColumn
     * @param array<string, mixed> $externalServices
     */
    protected function storeMappedData(
        AbstractMappedEntity $entity,
        Connection $connection,
        string $tableName,
        ?ClockInterface $clock = null,
        string|array $idColumn = 'id',
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
        foreach($idColumn as $colKey => $col) {
            $existsQuery
                ->andWhere("{$col} = :id{$colKey}")
                ->setParameter("id{$colKey}", $entityMappedData[$col]);
        }

        $storeQuery = $connection->createQueryBuilder();
        if ($existsQuery->fetchAssociative()) {
            $storeQuery
                ->update($tableName)
            ;
            foreach($idColumn as $colKey => $col) {
                $storeQuery
                    ->andWhere("{$col} = :id{$colKey}")
                    ->setParameter("id{$colKey}", $entityMappedData[$col]);
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
                ;
            }
        } else {
            $storeQuery
                ->insert($tableName)
            ;
            foreach ($entityMappedData as $column => $value) {
                $storeQuery
                    ->setValue($column, ":{$column}")
                    ->setParameter($column, $value)
                ;
            }
            if ($clock) {
                $storeQuery
                    ->setValue('created_at', ':now')
                    ->setValue('updated_at', ':now')
                ;
            }
        }
        if ($clock) {
            $storeQuery->setParameter('now', (string) $clock->getTime());
        }

        return (int) $storeQuery->executeStatement();
    }
}

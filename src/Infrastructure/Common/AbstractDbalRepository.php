<?php

declare(strict_types=1);

namespace App\Infrastructure\Common;

use App\Application\Common\Service\ClockInterface;
use App\Domain\Common\AbstractMappedEntity;
use Doctrine\DBAL\Connection;

abstract class AbstractDbalRepository
{
    /**
     * @param array<string, mixed> $externalServices
     */
    protected function storeMappedData(
        AbstractMappedEntity $entity,
        Connection $connection,
        string $tableName,
        ?ClockInterface $clock = null,
        string $idColumn = 'id',
        array $externalServices = []
    ): int {
        $entityMappedData = $entity->getMappedData($externalServices);
        $existsQuery = $connection->createQueryBuilder();
        $existsQuery
            ->select('*')
            ->from($tableName)
            ->where("{$idColumn} = :id")
            ->setParameter(
                'id',
                $entityMappedData[$idColumn]
            )
        ;

        $storeQuery = $connection->createQueryBuilder();
        if ($existsQuery->fetchAssociative()) {
            $storeQuery
                ->update($tableName)
                ->where("{$idColumn} = :id")
            ;
            foreach ($entityMappedData as $column => $value) {
                $storeQuery
                    ->set($column, ":{$column}")
                    ->setParameter($column, $value)
                ;
            }
            if ($clock) {
                $storeQuery
                    ->set('updated_at', ':now')
                    ->setParameter('now', $clock->getTime())
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
                    ->setParameter('now', $clock->getTime())
                ;
            }
        }

        return (int) $storeQuery->executeStatement();
    }
}

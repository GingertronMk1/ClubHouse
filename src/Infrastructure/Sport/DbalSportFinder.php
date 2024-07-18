<?php

declare(strict_types=1);

namespace App\Infrastructure\Sport;

use App\Application\Sport\SportFinderInterface;
use App\Application\Sport\SportModel;
use App\Domain\Common\ValueObject\DateTime;
use App\Domain\Sport\ValueObject\SportId;
use Doctrine\DBAL\Connection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DbalSportFinder implements SportFinderInterface
{
    private const TABLE_NAME = 'sports';

    public function __construct(
        private readonly Connection $connection,
    ) {
    }

    public function getById(SportId $id): SportModel
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
            throw new NotFoundHttpException('Team not found');
        }

        return $this->createFromRow($result);
    }

    public function getAll(array $sportIds = []): array
    {
        $query = $this->connection->createQueryBuilder();
        $query
            ->select('*')
            ->from(self::TABLE_NAME)
            ->orderBy('id')
        ;

        foreach ($sportIds as $n => $id) {
            $query
                ->orWhere("id = :id{$n}")
                ->setParameter("id{$n}", (string) $id)
            ;
        }

        return array_map(
            fn (array $row) => $this->createFromRow($row),
            $query->fetchAllAssociative()
        );
    }

    /**
     * @param array<string, mixed> $row
     */
    private function createFromRow(array $row): SportModel
    {
        $id = SportId::fromString($row['id']);

        /* @var TeamFinderInterface */
        return new SportModel(
            $id,
            $row['name'],
            $row['description'],
            DateTime::fromString($row['created_at']),
            DateTime::fromString($row['updated_at']),
            isset($row['deleted_at']) ? DateTime::fromString($row['deleted_at']) : null
        );
    }
}

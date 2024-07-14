<?php

declare(strict_types=1);

namespace App\Infrastructure\Team;

use App\Application\Person\PersonFinderInterface;
use App\Application\Team\TeamFinderInterface;
use App\Application\Team\TeamModel;
use App\Domain\Team\ValueObject\TeamId;
use Doctrine\DBAL\Connection;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DbalTeamFinder implements TeamFinderInterface
{
    private const TABLE_NAME = 'teams';

    public function __construct(
        private readonly Connection $connection,
        private readonly LoggerInterface $logger,
        private readonly PersonFinderInterface $personFinder
    ) {
    }

    public function getById(TeamId $id): TeamModel
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

    public function getAll(array $teamIds = []): array
    {
        $query = $this->connection->createQueryBuilder();
        $query
            ->select('*')
            ->from(self::TABLE_NAME)
            ->orderBy('id')
        ;

        foreach ($teamIds as $n => $id) {
            $query
                ->orWhere("id = :id{$n}")
                ->setParameter("id{$n}", (string) $id)
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

    /**
     * @param array<string, mixed> $row
     */
    private function createFromRow(array $row): TeamModel
    {
        return TeamModel::createFromRow($row, [
            PersonFinderInterface::class => $this->personFinder,
        ]);
    }
}

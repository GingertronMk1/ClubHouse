<?php

declare(strict_types=1);

namespace App\Infrastructure\Team;

use App\Application\Sport\SportFinderInterface;
use App\Application\Team\TeamFinderInterface;
use App\Application\Team\TeamModel;
use App\Domain\Common\ValueObject\DateTime;
use App\Domain\Sport\ValueObject\SportId;
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
        private readonly SportFinderInterface $sportFinder
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

    public function getForSport(SportId $id): array
    {
        $query = $this->connection->createQueryBuilder();
        $query
            ->select('*')
            ->from(self::TABLE_NAME)
            ->orderBy('id')
            ->where('sport_id = :sport_id')
            ->setParameter('sport_id', (string) $id)
        ;

        $result = $query->fetchAllAssociative();

        $returnVal = [];

        foreach ($result as $row) {
            try {
                $returnVal[] = $this->createFromRow($row);
            } catch (\Throwable $e) {
                $this->logger->error($e->getMessage());
                throw $e;
            }
        }

        return $returnVal;
    }

    /**
     * @param array<string, mixed> $row
     */
    private function createFromRow(array $row): TeamModel
    {
        $this->sportFinder->setRelationshipGetting(false);
        $deletedAt = null;
        if (isset($row['deleted_at'])) {
            $deletedAt = DateTime::fromString($row['deleted_at']);
        }

        $teamId = TeamId::fromString($row['id']);

        $sport = $this->sportFinder->getById(SportId::fromString($row['sport_id']));

        return new TeamModel(
            $teamId,
            $row['name'],
            $row['description'],
            $sport,
            DateTime::fromString($row['created_at']),
            DateTime::fromString($row['updated_at']),
            $deletedAt
        );
    }
}

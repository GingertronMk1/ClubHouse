<?php

declare(strict_types=1);

namespace App\Infrastructure\Match;

use App\Application\Match\MatchFinderInterface;
use App\Application\Match\MatchModel;
use App\Application\Sport\SportFinderInterface;
use App\Application\Team\TeamFinderInterface;
use App\Domain\Common\ValueObject\DateTime;
use App\Domain\Match\ValueObject\MatchId;
use App\Domain\Sport\ValueObject\SportId;
use App\Domain\Team\ValueObject\TeamId;
use Doctrine\DBAL\Connection;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DbalMatchFinder implements MatchFinderInterface
{
    private const TABLE_NAME = 'matches';

    public function __construct(
        private readonly Connection $connection,
        private readonly LoggerInterface $logger,
        private readonly SportFinderInterface $sportFinder,
        private readonly TeamFinderInterface $teamFinder,
    ) {
    }

    public function getById(MatchId $id): MatchModel
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
            throw new NotFoundHttpException('Match not found');
        }

        return MatchModel::createFromRow(
            $result,
            [
                SportFinderInterface::class => $this->sportFinder,
                TeamFinderInterface::class => $this->teamFinder,
            ]
        );
    }

    public function getAll(array $ids = []): array
    {
        $query = $this->connection->createQueryBuilder();
        $query
            ->select('*')
            ->from(self::TABLE_NAME)
            ->orderBy('id')
        ;

        foreach ($ids as $idIndex => $idValue) {
            $query
                ->orWhere("id = :id{$idIndex}")
                ->setParameter("id{$idIndex}", (string) $idValue)
            ;
        }

        $result = $query->fetchAllAssociative();

        $returnVal = [];

        foreach ($result as $row) {
            $returnVal[] = MatchModel::createFromRow(
                $row,
                [
                    SportFinderInterface::class => $this->sportFinder,
                    TeamFinderInterface::class => $this->teamFinder,
                ]
            );
        }

        return $returnVal;
    }

    private function createFromRow(array $row): MatchModel
    {
        $team1 = null;
        if (isset($row['team1_id'])) {
            $team1Id = TeamId::fromString($row['team1_id']);
            $team1 = $this->teamFinder->getById($team1Id);
        }

        $team2 = null;
        if (isset($row['team2_id'])) {
            $team2Id = TeamId::fromString($row['team2_id']);
            $team2 = $this->teamFinder->getById($team2Id);
        }

        $sport = null;
        if (isset($row['sport_id'])) {
            $sportId = SportId::fromString($row['sport_id']);
            $sport = $this->sportFinder->getById($sportId);
        }

        return new MatchModel(
            MatchId::fromString($row['id']),
            $row['name'],
            $row['details'],
            isset($row['start']) ? DateTime::fromString($row['start']) : null,
            $team1,
            $team2,
            $sport,
            DateTime::fromString($row['created_at']),
            DateTime::fromString($row['updated_at']),
            isset($row['deleted_at']) ? DateTime::fromString($row['deleted_at']) : null,
        );
    }
}

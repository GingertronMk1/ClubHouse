<?php

declare(strict_types=1);

namespace App\Application\Match;

use App\Application\Common\AbstractMappedModel;
use App\Application\Sport\SportFinderInterface;
use App\Application\Sport\SportModel;
use App\Application\Team\TeamFinderInterface;
use App\Application\Team\TeamModel;
use App\Domain\Common\ValueObject\DateTime;
use App\Domain\Match\ValueObject\MatchId;
use App\Domain\Sport\ValueObject\SportId;
use App\Domain\Team\ValueObject\TeamId;

class MatchModel extends AbstractMappedModel
{
    public function __construct(
        public readonly MatchId $id,
        public readonly string $name,
        public readonly ?string $details,
        public readonly ?DateTime $start,
        public readonly ?TeamModel $team1,
        public readonly ?TeamModel $team2,
        public readonly ?SportModel $sport,
        public readonly DateTime $createdAt,
        public readonly DateTime $updatedAt,
        public readonly ?DateTime $deletedAt,
    ) {
    }

    public static function createFromRow(array $row, array $externalServices = []): self
    {
        self::checkServicesExist(
            $externalServices,
            [
                TeamFinderInterface::class,
                SportFinderInterface::class,
            ]
        );

        /** @var TeamFinderInterface */
        $teamFinder = $externalServices[TeamFinderInterface::class];

        /** @var SportFinderInterface */
        $sportFinder = $externalServices[SportFinderInterface::class];

        $team1 = null;
        if (isset($row['team1_id'])) {
            $team1Id = TeamId::fromString($row['team1_id']);
            $team1 = $teamFinder->getById($team1Id);
        }

        $team2 = null;
        if (isset($row['team2_id'])) {
            $team2Id = TeamId::fromString($row['team2_id']);
            $team2 = $teamFinder->getById($team2Id);
        }

        $sport = null;
        if (isset($row['sport_id'])) {
            $sportId = SportId::fromString($row['sport_id']);
            $sport = $sportFinder->getById($sportId);
        }

        return new self(
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

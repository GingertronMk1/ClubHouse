<?php

declare(strict_types=1);

namespace App\Application\Team;

use App\Application\Common\AbstractMappedModel;
use App\Application\Person\PersonFinderInterface;
use App\Application\Person\PersonModel;
use App\Domain\Common\ValueObject\DateTime;
use App\Domain\Team\ValueObject\TeamId;

class TeamModel extends AbstractMappedModel
{
    /**
     * @param array<PersonModel> $people
     */
    public function __construct(
        public readonly TeamId $id,
        public readonly string $name,
        public readonly string $description,
        public readonly array $people,
        public readonly DateTime $createdAt,
        public readonly DateTime $updatedAt,
        public readonly ?DateTime $deletedAt,
    ) {
    }

    public static function createFromRow(array $row, array $externalServices = []): self
    {
        // Will except if not
        self::checkServicesExist(
            $externalServices,
            [PersonFinderInterface::class],
        );

        $deletedAt = null;
        if (isset($row['deleted_at'])) {
            $deletedAt = DateTime::fromString($row['deleted_at']);
        }

        $teamId = TeamId::fromString($row['id']);

        /**
         * @var PersonFinderInterface
         */
        $personFinder = $externalServices[PersonFinderInterface::class];

        $teamPeople = $personFinder->getForTeam($teamId);

        return new TeamModel(
            $teamId,
            $row['name'],
            $row['description'],
            $teamPeople,
            DateTime::fromString($row['created_at']),
            DateTime::fromString($row['updated_at']),
            $deletedAt
        );
    }
}

<?php

declare(strict_types=1);

namespace App\Application\MatchPerson;

use App\Application\Common\AbstractMappedModel;
use App\Application\Match\MatchFinderInterface;
use App\Application\Match\MatchModel;
use App\Application\Person\PersonFinderInterface;
use App\Application\Person\PersonModel;
use App\Domain\Match\ValueObject\MatchId;
use App\Domain\Person\ValueObject\PersonId;

class MatchPersonModel extends AbstractMappedModel
{
    public function __construct(
        public readonly MatchModel $match,
        public readonly PersonModel $person,
        public readonly string $role = ''
    ) {}

    public static function createFromRow(array $row, array $externalServices = []): self
    {
        self::checkServicesExist($externalServices, [
            MatchFinderInterface::class,
            PersonFinderInterface::class,
        ]);

        /** @var MatchFinderInterface */
        $matchFinder = $externalServices[MatchFinderInterface::class];

        /** @var PersonFinderInterface */
        $personFinder = $externalServices[PersonFinderInterface::class];

        return new self(
            $matchFinder->getById(MatchId::fromString($row['match_id'])),
            $personFinder->getById(PersonId::fromString($row['person_id'])),
            $row['role'] ?? ''
        );
    }
}

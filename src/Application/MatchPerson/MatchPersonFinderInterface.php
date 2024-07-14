<?php

declare(strict_types=1);

namespace App\Application\MatchPerson;

use App\Domain\Match\ValueObject\MatchId;
use App\Domain\Person\ValueObject\PersonId;

interface MatchPersonFinderInterface
{
    public function getByIds(MatchId $matchId, PersonId $personId): MatchPersonModel;

    /**
     * @return array<MatchPersonModel>
     */
    public function getForMatch(MatchId $matchId): array;
}

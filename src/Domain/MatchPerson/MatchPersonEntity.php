<?php

declare(strict_types=1);

namespace App\Domain\MatchPerson;

use App\Domain\Common\AbstractMappedEntity;
use App\Domain\Match\ValueObject\MatchId;
use App\Domain\Person\ValueObject\PersonId;

class MatchPersonEntity extends AbstractMappedEntity
{
    public function __construct(
        public readonly MatchId $matchId,
        public readonly PersonId $personId,
        public readonly ?string $role = ''
    ) {}

    public function getMappedData(array $externalServices = []): array
    {
        return [
            'match_id' => (string) $this->matchId,
            'person_id' => (string) $this->personId,
            'role' => $this->role,
        ];
    }
}

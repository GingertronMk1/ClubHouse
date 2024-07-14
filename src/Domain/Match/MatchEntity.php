<?php

declare(strict_types=1);

namespace App\Domain\Match;

use App\Domain\Common\AbstractMappedEntity;
use App\Domain\Common\ValueObject\DateTime;
use App\Domain\Match\ValueObject\MatchId;
use App\Domain\Sport\ValueObject\SportId;
use App\Domain\Team\ValueObject\TeamId;

class MatchEntity extends AbstractMappedEntity
{
    public function __construct(
        public readonly MatchId $id,
        public readonly string $name,
        public readonly ?string $details,
        public readonly ?DateTime $start,
        public readonly ?TeamId $team1Id,
        public readonly ?TeamId $team2Id,
        public readonly ?SportId $sportId
    ) {
    }

    public function getMappedData(array $externalServices = []): array
    {
        return [
            'id' => (string) $this->id,
            'name' => $this->name,
            'details' => $this->details,
            'start' => (string) $this->start,
            'team1_id' => (string) $this->team1Id,
            'team2_id' => (string) $this->team2Id,
            'sport_id' => (string) $this->sportId,
        ];
    }
}

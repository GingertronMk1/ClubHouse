<?php

declare(strict_types=1);

namespace App\Application\Match\Command;

use App\Application\Match\MatchModel;
use App\Domain\Common\ValueObject\DateTime;
use App\Domain\Match\ValueObject\MatchId;
use App\Domain\Sport\ValueObject\SportId;
use App\Domain\Team\ValueObject\TeamId;

class UpdateMatchCommand
{
    private function __construct(
        public readonly MatchId $id,
        public readonly string $name,
        public readonly ?string $details,
        public readonly ?DateTime $start,
        public readonly ?TeamId $team1Id,
        public readonly ?TeamId $team2Id,
        public readonly ?SportId $sportId
    ) {}

    public static function fromModel(MatchModel $match): self
    {
        return new self(
            $match->id,
            $match->name,
            $match->details,
            $match->start,
            $match->team1?->id,
            $match->team2?->id,
            $match->sport?->id
        );
    }
}

<?php

declare(strict_types=1);

namespace App\Application\Match\Command;

use App\Application\Match\MatchModel;
use App\Application\Sport\SportModel;
use App\Application\Team\TeamModel;
use App\Domain\Match\ValueObject\MatchId;

class UpdateMatchCommand
{
    private function __construct(
        public MatchId $id,
        public string $name,
        public ?string $details,
        public ?\DateTimeImmutable $start,
        public ?TeamModel $team1,
        public ?TeamModel $team2,
        public ?SportModel $sport
    ) {}

    public static function fromModel(MatchModel $match): self
    {
        return new self(
            $match->id,
            $match->name,
            $match->details,
            $match->start?->toDateTimeImmutable(),
            $match->team1,
            $match->team2,
            $match->sport
        );
    }
}

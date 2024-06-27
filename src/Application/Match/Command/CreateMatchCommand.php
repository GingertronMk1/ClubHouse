<?php

declare(strict_types=1);

namespace App\Application\Match\Command;

use App\Application\Sport\SportModel;
use App\Application\Team\TeamModel;
use App\Domain\Common\ValueObject\DateTime;
use App\Domain\Team\ValueObject\TeamId;

class CreateMatchCommand
{
    public function __construct(
        public string $name = '',
        public ?string $details = null,
        public ?\DateTime $start = null,
        public ?TeamModel $team1 = null,
        public ?TeamModel $team2 = null,
        public ?SportModel $sport = null
    ) {}
}

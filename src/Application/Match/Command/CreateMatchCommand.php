<?php

declare(strict_types=1);

namespace App\Application\Match\Command;

use App\Application\Sport\SportModel;
use App\Application\Team\TeamModel;

class CreateMatchCommand
{
    public function __construct(
        public string $name = '',
        public ?string $details = null,
        public ?\DateTimeImmutable $start = null,
        public ?TeamModel $team1 = null,
        public ?TeamModel $team2 = null,
        public ?SportModel $sport = null
    ) {}
}

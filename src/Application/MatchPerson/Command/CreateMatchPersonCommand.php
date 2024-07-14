<?php

declare(strict_types=1);

namespace App\Application\MatchPerson\Command;

use App\Application\Match\MatchModel;
use App\Application\Person\PersonModel;

class CreateMatchPersonCommand
{
    public function __construct(
        public MatchModel $match,
        public ?PersonModel $person = null,
        public ?string $role = ''
    ) {
    }
}

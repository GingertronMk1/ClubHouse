<?php

declare(strict_types=1);

namespace App\Application\Team\Command;

use App\Application\Sport\SportModel;
use App\Domain\Person\ValueObject\PersonId;

class CreateTeamCommand
{
    /**
     * @param array<PersonId> $people
     */
    public function __construct(
        public string $name = '',
        public string $description = '',
        public array $people = [],
        public ?SportModel $sport = null
    ) {
    }
}

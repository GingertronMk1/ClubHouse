<?php

declare(strict_types=1);

namespace App\Application\Person\Command;

use App\Domain\User\ValueObject\UserId;

class CreatePersonCommand
{
    public function __construct(
        public string $name = '',
        public ?UserId $userId = null
    ) {}
}

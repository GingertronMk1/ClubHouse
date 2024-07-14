<?php

declare(strict_types=1);

namespace App\Application\Person\Command;

use App\Application\User\UserModel;

class CreatePersonCommand
{
    public function __construct(
        public string $name = '',
        public ?UserModel $user = null
    ) {
    }
}

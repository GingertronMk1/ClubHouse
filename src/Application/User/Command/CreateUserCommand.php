<?php

namespace App\Application\User\Command;

class CreateUserCommand
{
    public function __construct(
        public string $email = '',
        public string $password = '',
    ) {
    }
}

<?php

declare(strict_types=1);

namespace App\Domain;

use App\Domain\User\ValueObject\UserId;

class User
{
    /**
     * Undocumented function.
     *
     * @param array<string> $roles
     */
    public function __construct(
        public readonly UserId $id,
        public readonly string $email,
        public readonly string $password,
        public readonly array $roles,
    ) {}
}

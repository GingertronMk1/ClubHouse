<?php

declare(strict_types=1);

namespace App\Application;

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
        public readonly array $roles,
    ) {}

    /**
     * Undocumented function.
     *
     * @return array<string>
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->id;
    }

    public function eraseCredentials(): void
    {
        // no op
    }
}

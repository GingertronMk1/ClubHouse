<?php

namespace App\Application;

use App\Domain\User\ValueObject\UserId;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    public function __construct(
        public readonly UserId $id,
        public readonly string $email,
        public readonly string $password,
        public readonly array $roles,
    ) {

    }

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

    public function getPassword(): ?string
    {
        return $this->password;
    }
}
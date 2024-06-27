<?php

declare(strict_types=1);

namespace App\Domain\User;

use App\Domain\Common\AbstractMappedEntity;
use App\Domain\User\ValueObject\UserId;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserEntity extends AbstractMappedEntity implements UserInterface, PasswordAuthenticatedUserInterface
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

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function eraseCredentials(): void
    {
        // no op
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getMappedData(array $externalServices = []): array
    {
        if (!isset($externalServices[UserPasswordHasherInterface::class])) {
            throw new \InvalidArgumentException('Need a hasher');
        }

        /** @var UserPasswordHasherInterface */
        $hasher = $externalServices[UserPasswordHasherInterface::class];

        return [
            'id' => (string) $this->id,
            'email' => $this->email,
            'password' => $hasher->hashPassword($this, $this->password),
        ];
    }
}

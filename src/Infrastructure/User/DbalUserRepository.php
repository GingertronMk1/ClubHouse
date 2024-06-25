<?php

namespace App\Infrastructure\User;

use App\Application\Common\Service\ClockInterface;
use App\Domain\User\User;
use App\Domain\User\UserRepositoryInterface;
use App\Domain\User\ValueObject\UserId;
use App\Infrastructure\Common\AbstractDbalRepository;
use Doctrine\DBAL\Connection;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class DbalUserRepository extends AbstractDbalRepository implements UserRepositoryInterface
{
    public function __construct(
        private readonly Connection $connection,
        private readonly UserPasswordHasherInterface $hasher,
        private readonly ClockInterface $clock
    ) {}

    public function generateId(): UserId
    {
        return UserId::generate();
    }

    public function store(User $user): UserId
    {
        $storePerson = $this->storeMappedData(
            entity: $user,
            connection: $this->connection,
            tableName: 'users',
            clock: $this->clock,
            externalServices: [
                UserPasswordHasherInterface::class => $this->hasher,
            ]
        );
        if (1 !== $storePerson) {
            throw new \Exception("Stored {$storePerson} users, when we should have stored just one!");
        }

        return $user->id;
    }
}

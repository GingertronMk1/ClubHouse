<?php

namespace App\Infrastructure\User;

use App\Domain\User\User;
use App\Domain\User\UserRepositoryInterface;
use App\Domain\User\ValueObject\UserId;
use Doctrine\DBAL\Connection;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class DbalUserRepository implements UserRepositoryInterface
{
    public function __construct(
        private readonly Connection $connection,
        private readonly UserPasswordHasherInterface $hasher,
    ) {}

    public function generateId(): UserId
    {
        return UserId::generate();
    }

    public function store(User $user): UserId
    {
        $existsQuery = $this->connection->createQueryBuilder();
        $existsQuery->select('*')->from('users')->where('id = :id')->setParameter('id', (string) $user->id);

        $hashedPassword = $this->hasher->hashPassword($user, $user->password);
        if ($existsQuery->fetchAssociative()) {
            $updateQuery = $this->connection->createQueryBuilder();
            $updateQuery
                ->update('users')
                ->where('id = :id')
                ->set('email', ':email')
                ->set('password', ':password')
                ->setParameters([
                    'id' => (string) $user->id,
                    'email' => $user->email,
                    'password' => $hashedPassword,
                ])
            ;
            $updateQuery->executeStatement();
        } else {
            $insertQuery = $this->connection->createQueryBuilder();
            $insertQuery
                ->insert('users')
                ->values([
                    'id' => ':id',
                    'email' => ':email',
                    'password' => ':password',
                ])
                ->setParameters([
                    'id' => (string) $user->id,
                    'email' => $user->email,
                    'password' => $hashedPassword,
                ])
            ;
            $insertQuery->executeStatement();
        }

        return $user->id;
    }
}

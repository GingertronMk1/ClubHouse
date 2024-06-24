<?php

namespace App\Infrastructure\User;

use App\Domain\User\User;
use App\Domain\User\UserRepositoryInterface;
use App\Domain\User\ValueObject\UserId;
use Doctrine\DBAL\Connection;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class DbalUserRepository implements UserRepositoryInterface
{
    private const TABLE_NAME = 'users';

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
        $existsQuery->select('*')->from(self::TABLE_NAME)->where('id = :id')->setParameter('id', (string) $user->id);

        $hashedPassword = $this->hasher->hashPassword($user, $user->password);
        if ($existsQuery->fetchAssociative()) {
            $updateQuery = $this->connection->createQueryBuilder();
            $updateQuery
                ->update(self::TABLE_NAME)
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
            $emailExistsQuery = $this->connection->createQueryBuilder();
            $emailExistsQuery
                ->select('*')
                ->from(self::TABLE_NAME)
                ->where('email = :email')
                ->setParameter('email', $user->email)
            ;
            if ($emailExistsQuery->fetchAssociative()) {
                throw new \InvalidArgumentException('User with that email already exists');
            }
            $insertQuery = $this->connection->createQueryBuilder();
            $insertQuery
                ->insert(self::TABLE_NAME)
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

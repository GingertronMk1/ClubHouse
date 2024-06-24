<?php

namespace App\Application\User\CommandHandler;

use App\Application\User\Command\CreateUserCommand;
use App\Domain\User\User;
use App\Domain\User\UserRepositoryInterface;
use App\Domain\User\ValueObject\UserId;

class CreateUserCommandHandler
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {}

    public function handle(CreateUserCommand $command): UserId
    {
        $id = $this->userRepository->generateId();
        $user = new User($id, $command->email, $command->password, []);

        return $this->userRepository->store($user);
    }
}

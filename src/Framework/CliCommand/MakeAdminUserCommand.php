<?php

namespace App\Framework\CliCommand;

use App\Domain\User\UserEntity;
use App\Domain\User\UserRepositoryInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:make-admin-user',
    description: 'Create an admin user with a default username and password',
)]
class MakeAdminUserCommand extends Command
{
    private const ADMIN_EMAIL = 'test@clubhouse.test';
    private const ADMIN_PASSWORD = '12345';

    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        // $this
        //     ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
        //     ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        // ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $user = new UserEntity(
            $this->userRepository->generateId(),
            self::ADMIN_EMAIL,
            self::ADMIN_PASSWORD,
            []
        );

        $this->userRepository->store($user);

        $io->success('User created');

        return Command::SUCCESS;
    }
}

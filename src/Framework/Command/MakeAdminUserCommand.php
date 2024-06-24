<?php

namespace App\Framework\Command;

use App\Domain\User\User;
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
        $user = new User(
            $this->userRepository->generateId(),
            'test@clubhouse.test',
            '12345',
            []
        );

        $this->userRepository->store($user);

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}

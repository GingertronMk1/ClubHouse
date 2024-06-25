<?php

namespace App\Framework\CliCommand;

use App\Domain\Common\ValueObject\AbstractUuidId;
use App\Domain\Person\Person;
use App\Domain\Person\PersonRepositoryInterface;
use App\Domain\Team\Team;
use App\Domain\Team\TeamRepositoryInterface;
use App\Domain\User\User;
use App\Domain\User\UserRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\HttpKernel\KernelInterface;

#[AsCommand(
    name: 'app:load-fixtures',
    description: 'Load app fixtures',
)]
class LoadFixturesCommand extends Command
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepositoryInterface,
        private readonly PersonRepositoryInterface $personRepositoryInterface,
        private readonly TeamRepositoryInterface $teamRepositoryInterface
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        foreach ($io->progressIterate(range(1, 15)) as $value) {
            $user = new User(
                $this->userRepositoryInterface->generateId(),
                "testUser{$value}@clubhouse.test",
                "12345",
                []
            );
            $userId = $this->userRepositoryInterface->store($user);

            $person = new Person(
                $this->personRepositoryInterface->generateId(),
                "Test Person {$value}",
                $userId
            );
            $personId = $this->personRepositoryInterface->store($person);

            $team = new Team(
                $this->teamRepositoryInterface->generateId(),
                "Test Team {$value}",
                $value % 2 ? "This is test team number {$value}." : '',
                []
            );
            $teamId = $this->teamRepositoryInterface->store($team);
        }

        return Command::SUCCESS;
    }
}

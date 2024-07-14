<?php

namespace App\Framework\CliCommand;

use App\Domain\Person\PersonEntity;
use App\Domain\Person\PersonRepositoryInterface;
use App\Domain\Sport\SportEntity;
use App\Domain\Sport\SportRepositoryInterface;
use App\Domain\Team\TeamEntity;
use App\Domain\Team\TeamRepositoryInterface;
use App\Domain\User\UserEntity;
use App\Domain\User\UserRepositoryInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:load-fixtures',
    description: 'Load app fixtures',
)]
class LoadFixturesCliCommand extends Command
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepositoryInterface,
        private readonly PersonRepositoryInterface $personRepositoryInterface,
        private readonly TeamRepositoryInterface $teamRepositoryInterface,
        private readonly SportRepositoryInterface $sportRepository
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
            $user = new UserEntity(
                $this->userRepositoryInterface->generateId(),
                "testUser{$value}@clubhouse.test",
                '12345',
                []
            );
            $userId = $this->userRepositoryInterface->store($user);

            $person = new PersonEntity(
                $this->personRepositoryInterface->generateId(),
                "Test Person {$value}",
                $userId
            );
            $personId = $this->personRepositoryInterface->store($person);

            $team = new TeamEntity(
                $this->teamRepositoryInterface->generateId(),
                "Test Team {$value}",
                $value % 2 ? "This is test team number {$value}." : '',
                []
            );
            $teamId = $this->teamRepositoryInterface->store($team);
        }

        foreach ($this->getSports() as $sportName => $sportDescription) {
            $sport = new SportEntity(
                $this->sportRepository->generateId(),
                $sportName,
                $sportDescription
            );

            $this->sportRepository->store($sport);
        }

        return Command::SUCCESS;
    }

    /**
     * @return array<string, string>
     */
    private function getSports(): array
    {
        return [
            'Rugby League' => '13 people a side and an oval ball',
            'Rugby Union' => '15 people a side and an oval ball',
            'American Football' => '11 people a side and an oval ball, but you\'re allowed to throw it forwards',
            'Canadian Football' => '11 people a side and an oval ball, but you\'re allowed to throw it forwards, the field\'s bigger, and there\'s only 3 downs.',
        ];
    }
}

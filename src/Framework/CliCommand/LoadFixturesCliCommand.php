<?php

namespace App\Framework\CliCommand;

use App\Domain\Person\PersonEntity;
use App\Domain\Person\PersonRepositoryInterface;
use App\Domain\Person\ValueObject\PersonId;
use App\Domain\Sport\SportEntity;
use App\Domain\Sport\SportRepositoryInterface;
use App\Domain\Sport\ValueObject\SportId;
use App\Domain\Team\TeamEntity;
use App\Domain\Team\TeamRepositoryInterface;
use App\Domain\Team\ValueObject\TeamId;
use App\Domain\User\UserEntity;
use App\Domain\User\UserRepositoryInterface;
use App\Domain\User\ValueObject\UserId;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;

#[AsCommand(
    name: 'app:load-fixtures',
    description: 'Load app fixtures',
)]
class LoadFixturesCliCommand extends Command
{
    private const TEAMS_PER_SPORT = 4;
    private readonly Generator $faker;
    private int $personIndex = 1;
    private const PROGRESS_BAR_FORMAT = '%current%/%max% [%bar%] %percent:3s%% %elapsed:16s%/%estimated:-16s% %message%';

    public function __construct(
        private readonly Filesystem $filesystem,
        private readonly UserRepositoryInterface $userRepository,
        private readonly PersonRepositoryInterface $personRepository,
        private readonly TeamRepositoryInterface $teamRepository,
        private readonly SportRepositoryInterface $sportRepository
    ) {
        parent::__construct();
        $this->faker = Factory::create('en_GB');
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (!$output instanceof ConsoleOutputInterface) {
            $output->writeln('Not a console output');

            return Command::FAILURE;
        }

        $io = new SymfonyStyle($input, $output);

        $sportsJson = $this
            ->filesystem
            ->readfile('assets/fixtures/fixtures.json')
        ;

        /** @var array<string, array<mixed>> */
        $things = json_decode($sportsJson, true);

        $io->title('Sports');
        $sportBar = $io->createProgressBar();
        $sportBar->setFormat(self::PROGRESS_BAR_FORMAT);

        foreach($sportBar->iterate($things['sports']) as $sport) {
            $sportEntity = new SportEntity(
                SportId::fromString($sport['id']),
                $sport['name'],
                $sport['description']
            );
            $sportBar->setMessage($sportEntity->name);
            $this->sportRepository->store($sportEntity);
        }

        $io->newLine(4);

        $io->title('People');
        $peopleBar = $io->createProgressBar();
        $peopleBar->setFormat(self::PROGRESS_BAR_FORMAT);
        foreach($peopleBar->iterate($things['people']) as $person) {
            $userId = null;
            if ($person['user'] ?? false) {
                $personUser = $person['user'];
                $userEntity = new UserEntity(
                    UserId::fromString($personUser['id']),
                    $personUser['email'],
                    '12345',
                    []
                );
                $userId = $this->userRepository->store($userEntity);
            }

            $personEntity = new PersonEntity(
                PersonId::fromString($person['id']),
                $person['name'],
                $userId
            );
            $peopleBar->setMessage($personEntity->name);
            $this->personRepository->store($personEntity);
        }
        $io->newLine(4);

        $io->title('Teams');
        $teamBar = $io->createProgressBar();
        $teamBar->setFormat(self::PROGRESS_BAR_FORMAT);
        foreach($teamBar->iterate($things['teams']) as $team) {
            $teamEntity = new TeamEntity(
                TeamId::fromString($team['id']),
                $team['name'],
                $team['description'],
                array_map(fn (array $person) => PersonId::fromString($person['id']), $team['people']),
                SportId::fromString($team['sport']['id'])
            );
            $teamBar->setMessage($teamEntity->name);
            $this->teamRepository->store($teamEntity);
        }

        $io->newLine(4);

        return Command::SUCCESS;
    }
}

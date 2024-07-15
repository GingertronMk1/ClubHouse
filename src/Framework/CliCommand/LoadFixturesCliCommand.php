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
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

#[AsCommand(
    name: 'app:load-fixtures',
    description: 'Load app fixtures',
)]
class LoadFixturesCliCommand extends Command
{
    private const TEAMS_PER_SPORT = 4;
    private const PROGRESS_FORMAT = '%current%/%max% [%bar%] %percent:3s%% %elapsed:16s%/%estimated:-16s% %message%';
    private readonly Generator $faker;
    private int $personIndex = 1;

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

        $sportsJson = $this
            ->filesystem
            ->readfile('assets/fixtures/sports.json')
        ;

        $sports = json_decode($sportsJson, true);

        $sportSection = $output->section();
        $teamSection = $output->section();
        $peopleSection = $output->section();

        $sportProgressBar = new ProgressBar($sportSection);
        $peopleProgressBar = new ProgressBar($peopleSection);
        $teamProgressBar = new ProgressBar($teamSection);

        $sportProgressBar->setFormat(self::PROGRESS_FORMAT);
        $peopleProgressBar->setFormat(self::PROGRESS_FORMAT);
        $teamProgressBar->setFormat(self::PROGRESS_FORMAT);

        $sportProgressBar->setMessage('');
        $peopleProgressBar->setMessage('');
        $teamProgressBar->setMessage('');

        $sportProgressBar->setMessage('Sports');

        foreach ($sportProgressBar->iterate($sports) as $name => $rosterSize) {
            $sportProgressBar->setMessage($name);
            $sport = new SportEntity(
                $this->sportRepository->generateId(),
                $name,
                "Played with game day rosters of {$rosterSize}."
            );
            $sportId = $this->sportRepository->store($sport);

            $teamProgressBar->start(self::TEAMS_PER_SPORT);

            for ($i = 0; $i < self::TEAMS_PER_SPORT; ++$i) {
                $this->createTeam($rosterSize, $sportId);
                $teamProgressBar->advance();
            }
            $teamProgressBar->finish();
        }

        return Command::SUCCESS;
    }

    private function createTeam(int $rosterSize, SportId $sportId): TeamId
    {
        $peopleIds = [];
        $peopleInSport = $rosterSize * 2;
        for ($i = 0; $i < $peopleInSport; ++$i) {
            $peopleIds[] = $this->createPerson();
        }
        $team = new TeamEntity(
            $this->teamRepository->generateId(),
            $this->faker->company(),
            $this->faker->realText(),
            $peopleIds,
            $sportId
        );

        return $this->teamRepository->store($team);
    }

    private function createPerson(): PersonId
    {
        $name = $this->faker->name();
        $userEntity = new UserEntity(
            $this->userRepository->generateId(),
            ++$this->personIndex.strtolower(preg_replace(['/[^A-Za-z ]/', '/\s/'], ['', '.'], $name).'@clubhouse.test'),
            '12345',
            []
        );
        $userId = $this->userRepository->store($userEntity);
        $personEntity = new PersonEntity(
            $this->personRepository->generateId(),
            $name,
            $userId
        );

        return $this->personRepository->store($personEntity);
    }
}

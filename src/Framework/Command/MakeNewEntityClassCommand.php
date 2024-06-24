<?php

namespace App\Framework\Command;

use App\Domain\Common\ValueObject\AbstractUuidId;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\HttpKernel\KernelInterface;

#[AsCommand(
    name: 'app:make-new-entity-class',
    description: 'Add a short description for your command',
)]
class MakeNewEntityClassCommand extends Command
{
    private const NAME_ARG = 'className';

    public function __construct(
        private readonly KernelInterface $kernel
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument(self::NAME_ARG, InputArgument::REQUIRED, 'The name of the new entity class')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument(self::NAME_ARG);

        if ($arg1) {
            $io->note(sprintf('You passed an argument: %s', $arg1));
        }

        foreach($this->getClassFileNames() as $classFileName => $information) {
            $replacedFileName = preg_replace( '/{ENTITY}/', $arg1, $classFileName);
            $fqn = preg_replace(
                ['/\//', '/^src/'],
                ['\\', $arg1, 'App'],
                $replacedFileName
            );
            $lastBackslash = strrpos($fqn, '\\');
            $nameSpace = substr($fqn, 0, $lastBackslash);
            $className = substr($fqn, $lastBackslash + 1);
            $io->info([$fqn, $nameSpace, $className]);

            $fileDelimiter = strrpos($replacedFileName, '/');

            $dir = substr($replacedFileName, 0, $fileDelimiter);

            try {
                $io->info("Creating `$dir`");
                mkdir($dir, recursive: true);
            } catch (\Throwable $e) {
                $io->error($e->getMessage());
            }
            $fileNameExtended = "{$replacedFileName}.php";
            $io->info("Creating `{$fileNameExtended}");
            fopen($fileNameExtended, 'w');
        }


        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }

    private function getClassFileNames(): array
    {
        return [
            'src/Domain/{ENTITY}/ValueObject/{ENTITY}Id' => [
                'extends' => AbstractUuidId::class,
            ],
            'src/Domain/{ENTITY}/{ENTITY}RepositoryInterface' => null,
            'src/Domain/{ENTITY}/{ENTITY}' => null,
            'src/Application/{ENTITY}/Command/Create{ENTITY}Command' => null,
            'src/Application/{ENTITY}/Command/Edit{ENTITY}Command' => null,
            'src/Application/{ENTITY}/CommandHandler/Create{ENTITY}CommandHandler' => null,
            'src/Application/{ENTITY}/CommandHandler/Edit{ENTITY}CommandHandler' => null,
            'src/Application/{ENTITY}/{ENTITY}FinderInterface' => null,
            'src/Application/{ENTITY}/{ENTITY}' => null,
            'src/Infrastructure/{ENTITY}/Dbal{ENTITY}Finder' => null,
            'src/Infrastructure/{ENTITY}/Dbal{ENTITY}Repository' => null,
            'src/Framework/Controller/{ENTITY}Controller' => [
                'extends' => AbstractController::class
            ],
            'src/Framework/Form/{ENTITY}/Create{ENTITY}Type' => [
                'extends' => AbstractType::class,
            ],
            'src/Framework/Form/{ENTITY}/Edit{ENTITY}Type' => [
                'extends' => AbstractType::class
            ],
        ];
    }
}

<?php

namespace App\Framework\CliCommand;

use App\Application\Common\AbstractMappedModel;
use App\Application\Common\Service\ClockInterface;
use App\Domain\Common\AbstractMappedEntity;
use App\Domain\Common\ValueObject\AbstractUuidId;
use App\Domain\Util\EntityClass;
use App\Infrastructure\Common\AbstractDbalRepository;
use Doctrine\DBAL\Connection;
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
    name: 'app:make-new-entity-class',
    description: 'Add a short description for your command',
)]
class MakeNewEntityClassCliCommand extends Command
{
    private const INFORMATION_ATTRIBUTES_STRING = 'attributes';
    private const INFORMATION_IMPLEMENTS_STRING = 'implements';
    private const INFORMATION_EXTENDS_STRING = 'extends';
    private const INFORMATION_TYPE_STRING = 'type';

    private const NAME_ARG = 'className';

    public function __construct(
        private readonly KernelInterface $kernel
    ) {
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

        foreach ($this->getClassFileNames() as $classFileName => $information) {
            $replacedFileName = preg_replace('/{ENTITY}/', $arg1, $classFileName);
            $replacedFileName = $this->kernel->getProjectDir().'/'.$replacedFileName;
            $fqn = preg_replace(
                ['/\//', '/^.*src/'],
                ['\\', 'App'],
                $replacedFileName
            );

            if (!is_string($fqn)) {
                throw new \Exception("Something's gone wrong");
            }
            $lastBackslash = strrpos($fqn, '\\');

            if (false === $lastBackslash) {
                throw new \Exception("Something's gone wrong");
            }

            $nameSpace = substr($fqn, 0, $lastBackslash);
            $className = substr($fqn, $lastBackslash + 1);
            $io->info([$fqn, $nameSpace, $className]);

            $fileDelimiter = strrpos($replacedFileName, '/');

            if (false === $fileDelimiter) {
                throw new \Exception("Something's gone wrong");
            }

            $dir = substr($replacedFileName, 0, $fileDelimiter);

            try {
                $io->info("Creating `{$dir}`");
                mkdir($dir, recursive: true);
            } catch (\Throwable $e) {
                $io->error($e->getMessage());
            }
            $fileNameExtended = "{$replacedFileName}.php";
            $io->info("Creating `{$fileNameExtended}");
            $file = fopen($fileNameExtended, 'w');
            if (!$file) {
                throw new \Exception("Something's gone wrong");
            }
            fwrite($file, <<<EOF
            <?php

            declare(strict_types=1);

            namespace {$nameSpace};

            {$information->type} {$className} {$information->getExtends()} {$information->getImplements()}
            {
                public function __construct(
                    {$information->getAttributes()}
                )
                {
                }
            }
            
            EOF);
        }

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }

    /**
     * @return array<string, EntityClass>
     */
    private function getClassFileNames(): array
    {
        return [
            'src/Domain/{ENTITY}/ValueObject/{ENTITY}Id' => new EntityClass(
                extends:  AbstractUuidId::class,
                ),
            'src/Domain/{ENTITY}/{ENTITY}RepositoryInterface' => new EntityClass(
                type: 'interface'
            ),
            'src/Domain/{ENTITY}/{ENTITY}Entity' => new EntityClass(
                extends: AbstractMappedEntity::class
            ),
            'src/Application/{ENTITY}/Command/Create{ENTITY}Command' => null,
            'src/Application/{ENTITY}/Command/Update{ENTITY}Command' => null,
            'src/Application/{ENTITY}/CommandHandler/Create{ENTITY}CommandHandler' => null,
            'src/Application/{ENTITY}/CommandHandler/Update{ENTITY}CommandHandler' => null,
            'src/Application/{ENTITY}/{ENTITY}FinderInterface' => new EntityClass(
                type: 'interface',
            ),
            'src/Application/{ENTITY}/{ENTITY}Model' => new EntityClass(
                extends: AbstractMappedModel::class,
            ),
            'src/Infrastructure/{ENTITY}/Dbal{ENTITY}Finder' => new EntityClass(
                attributes: [
                    Connection::class => 'private readonly'
                ]
            ),
            'src/Infrastructure/{ENTITY}/Dbal{ENTITY}Repository' => new EntityClass(
                extends: AbstractDbalRepository::class,
                attributes: [
                    Connection::class => 'private readonly',
                    ClockInterface::class => 'private readonly',
                ]
            ),
            'src/Framework/Controller/{ENTITY}Controller' => new EntityClass(
                extends: AbstractController::class
            ),
            'src/Framework/Form/{ENTITY}/Create{ENTITY}FormType' => new EntityClass(
                extends: AbstractType::class
            ),
            'src/Framework/Form/{ENTITY}/Update{ENTITY}FormType' => new EntityClass(
                extends: AbstractType::class
            ),

        ];
    }
}

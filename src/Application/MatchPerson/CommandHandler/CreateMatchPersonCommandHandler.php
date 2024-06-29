<?php

declare(strict_types=1);

namespace App\Application\MatchPerson\CommandHandler;

use App\Application\MatchPerson\Command\CreateMatchPersonCommand;
use App\Domain\Match\ValueObject\MatchId;
use App\Domain\MatchPerson\MatchPersonEntity;
use App\Domain\MatchPerson\MatchPersonRepositoryInterface;

class CreateMatchPersonCommandHandler
{
    public function __construct(
        private readonly MatchPersonRepositoryInterface $matchPersonRepository
    ) {}

    public function handle(CreateMatchPersonCommand $command): MatchId
    {
        if (!$command->person) {
            throw new \Exception();
        }
        $entity = new MatchPersonEntity(
            $command->match->id,
            $command->person->id,
            $command->role
        );

        return $this->matchPersonRepository->store($entity);
    }
}

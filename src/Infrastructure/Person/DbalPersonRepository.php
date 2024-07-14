<?php

declare(strict_types=1);

namespace App\Infrastructure\Person;

use App\Application\Common\Service\ClockInterface;
use App\Domain\Person\PersonEntity;
use App\Domain\Person\PersonRepositoryInterface;
use App\Domain\Person\ValueObject\PersonId;
use App\Infrastructure\Common\AbstractDbalRepository;
use Doctrine\DBAL\Connection;

class DbalPersonRepository extends AbstractDbalRepository implements PersonRepositoryInterface
{
    public function __construct(
        private readonly Connection $connection,
        private readonly ClockInterface $clock
    ) {
    }

    public function generateId(): PersonId
    {
        return PersonId::generate();
    }

    public function store(PersonEntity $person): PersonId
    {
        $storePerson = $this->storeMappedData(
            $person,
            $this->connection,
            'people',
            $this->clock
        );
        if (1 !== $storePerson) {
            throw new \Exception("Stored {$storePerson} people, when we should have stored just one!");
        }

        return $person->id;
    }
}

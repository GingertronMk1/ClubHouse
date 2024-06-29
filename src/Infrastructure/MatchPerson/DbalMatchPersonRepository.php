<?php

declare(strict_types=1);

namespace App\Infrastructure\MatchPerson;

use App\Application\Common\Service\ClockInterface;
use App\Infrastructure\Common\AbstractDbalRepository;
use Doctrine\DBAL\Connection;

class DbalMatchPersonRepository extends AbstractDbalRepository
{
    public function __construct(
        private readonly Connection $connection,
        private readonly ClockInterface $clockInterface
    ) {}
}

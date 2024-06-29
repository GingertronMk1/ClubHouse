<?php

declare(strict_types=1);

namespace App\Infrastructure\MatchPerson;

use App\Application\Common\Service\ClockInterface;
use Doctrine\DBAL\Connection;

class DbalMatchPersonRepository extends App\Infrastructure\Common\AbstractDbalRepository
{
    public function __construct(
        private readonly Connection $connection,
        private readonly ClockInterface $clockInterface
    ) {}
}

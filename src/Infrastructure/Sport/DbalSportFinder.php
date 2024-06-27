<?php

declare(strict_types=1);

namespace App\Infrastructure\Sport;

use Doctrine\DBAL\Connection;

class DbalSportFinder
{
    public function __construct(
        private readonly Connection $connection,
    ) {}
}

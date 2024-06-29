<?php

declare(strict_types=1);

namespace App\Infrastructure\MatchPerson;

use Doctrine\DBAL\Connection;

class DbalMatchPersonFinder
{
    public function __construct(private readonly Connection $connection) {}
}

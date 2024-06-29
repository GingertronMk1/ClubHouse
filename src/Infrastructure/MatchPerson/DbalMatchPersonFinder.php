<?php

declare(strict_types=1);

namespace App\Infrastructure\MatchPerson;

class DbalMatchPersonFinder  
{
    public function __construct(private readonly \Doctrine\DBAL\Connection $connection) {}
}

<?php

declare(strict_types=1);

namespace App\Infrastructure\MatchPerson;

class DbalMatchPersonRepository extends  
{
    public function __construct(private readonly \Doctrine\DBAL\Connection $connection,
private readonly \App\Application\Common\Service\ClockInterface $clockInterface) {}
}

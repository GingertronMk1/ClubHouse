<?php

declare(strict_types=1);

namespace App\Infrastructure\Sport;

use App\Infrastructure\Common\AbstractDbalRepository;
use Doctrine\DBAL\Connection;

class DbalSportRepository extends AbstractDbalRepository
{
    public function __construct(
        private readonly Connection $connection,
    ) {}
}

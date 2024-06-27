<?php

declare(strict_types=1);

namespace App\Domain\Sport;

use App\Domain\Sport\ValueObject\SportId;

interface SportRepositoryInterface
{
    public function generateId(): SportId;
    public function store(SportEntity $sport): SportId;
}

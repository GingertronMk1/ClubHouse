<?php

declare(strict_types=1);

namespace App\Application\Sport;

use App\Domain\Sport\ValueObject\SportId;

interface SportFinderInterface
{
    public function getById(SportId $id): SportModel;

    /**
     * @param array<SportId> $sportIds
     * @return array<SportModel>
     */
    public function getAll(array $sportIds = []): array;
}

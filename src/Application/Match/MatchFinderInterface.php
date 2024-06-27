<?php

declare(strict_types=1);

namespace App\Application\Match;

use App\Domain\Match\ValueObject\MatchId;

interface MatchFinderInterface
{
    public function getById(MatchId $id): MatchModel;

    /**
     * @param array<MatchId> $ids
     *
     * @return array<MatchModel>
     */
    public function getAll(array $ids = []): array;
}

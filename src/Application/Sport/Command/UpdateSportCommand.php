<?php

declare(strict_types=1);

namespace App\Application\Sport\Command;

use App\Application\Sport\SportModel;
use App\Domain\Sport\ValueObject\SportId;

class UpdateSportCommand
{
    private function __construct(
        public SportId $id,
        public string $name,
        public ?string $description,
    ) {
    }

    public static function fromModel(SportModel $model): self
    {
        return new self(
            $model->id,
            $model->name,
            $model->description
        );
    }
}

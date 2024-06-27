<?php

declare(strict_types=1);

namespace App\Domain\Common;

abstract class AbstractMappedEntity
{
    /**
     * @param array<string, mixed> $externalServices
     *
     * @return array<string, string>
     */
    abstract public function getMappedData(array $externalServices = []): array;
}

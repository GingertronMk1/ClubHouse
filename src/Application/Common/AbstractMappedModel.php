<?php

declare(strict_types=1);

namespace App\Application\Common;

abstract class AbstractMappedModel
{
    /**
     * @param array<string, mixed> $externalServices
     */
    abstract public static function createFromRow(array $externalServices = []): static;

    /**
     * @param array<string, mixed> $externalServices
     * @param array<string>        $expectedServiceNames
     */
    protected function checkServicesExist(array $externalServices, array $expectedServiceNames): bool
    {
        $externalServiceNames = array_keys($externalServices);
        $diff = array_diff($expectedServiceNames, $externalServiceNames);
        if (!empty($diff)) {
            $diffString = implode(', ', $diff);

            throw new \InvalidArgumentException("`{$diffString}` missing from external services");
        }

        return true;
    }
}

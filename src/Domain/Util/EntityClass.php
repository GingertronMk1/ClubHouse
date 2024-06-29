<?php

declare(strict_types=1);

namespace App\Domain\Util;

class EntityClass
{
    public readonly array $implements;
    public readonly array $extends;

    public function __construct(
        array|string $implements = [],
        array|string $extends = [],
        public readonly array $attributes = [],
        public readonly string $type = 'class'
    ) {
        $this->implements = is_string($implements) ? [$implements] : $implements;
        $this->extends = is_string($extends) ? [$extends] : $extends;
    }

    public function getExtends(): string
    {
        return $this->getVal('extends', $this->implements);
    }
    public function getImplements(): string
    {
        return $this->getVal('implements', $this->implements);
    }
    public function getAttributes(): string
    {
        $returnVal = '';
        foreach($this->attributes as $attrClass => $attrMod) {
            $returnVal .= "$attrMod $attrClass," . PHP_EOL;
        }
        return $returnVal;
    }

    private function getVal(string $type, array $val): string
    {
        return $type . ' ' . implode(', ', $this->implements);
    }
}

<?php

declare(strict_types=1);

namespace App\Domain\Person\ValueObject;

class PersonId extends \App\Domain\Common\ValueObject\AbstractUuidId
{
    public function __construct(
    )
    {
    }
}

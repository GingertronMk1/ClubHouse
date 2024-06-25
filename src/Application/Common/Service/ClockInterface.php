<?php

namespace App\Application\Common\Service;

use App\Domain\Common\ValueObject\DateTime;

interface ClockInterface
{
    public function getTime(?string $modifier = null): DateTime;
}

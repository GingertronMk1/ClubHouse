<?php

declare(strict_types=1);

namespace App\Application\User;

use App\Application\Common\AbstractMappedModel;
use App\Domain\User\ValueObject\UserId;

class UserModel extends AbstractMappedModel
{
    /**
     * Undocumented function.
     *
     * @param array<string> $roles
     */
    public function __construct(
        public readonly UserId $id,
        public readonly string $email,
        public readonly array $roles,
    ) {
    }

    public static function createFromRow(array $row, array $externalServices = []): self
    {
        return new self(
            UserId::fromString($row['id']),
            $row['email'],
            []
        );
    }
}

<?php

declare(strict_types=1);

namespace App\Application\Person;

use App\Application\Common\AbstractMappedModel;
use App\Application\User\UserFinderInterface;
use App\Application\User\UserModel;
use App\Domain\Common\ValueObject\DateTime;
use App\Domain\Person\ValueObject\PersonId;
use App\Domain\User\ValueObject\UserId;

class PersonModel extends AbstractMappedModel
{
    public function __construct(
        public readonly PersonId $id,
        public readonly string $name,
        public readonly ?UserModel $user,
        public readonly DateTime $createdAt,
        public readonly DateTime $updatedAt,
        public readonly ?DateTime $deletedAt,
    ) {
    }

    public static function createFromRow(array $row, array $externalServices = []): self
    {
        self::checkServicesExist(
            $externalServices,
            [UserFinderInterface::class],
        );

        /** @var UserFinderInterface */
        $userFinder = $externalServices[UserFinderInterface::class];

        $user = null;
        if (isset($row['user_id'])) {
            $user = $userFinder->getById(UserId::fromString($row['user_id']));
        }

        return new PersonModel(
            PersonId::fromString($row['id']),
            $row['name'],
            $user,
            DateTime::fromString($row['created_at']),
            DateTime::fromString($row['updated_at']),
            isset($row['deleted_at']) ? DateTime::fromString($row['deleted_at']) : null
        );
    }
}

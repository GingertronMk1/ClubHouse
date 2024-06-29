<?php

declare(strict_types=1);

namespace App\Infrastructure\MatchPerson;

use App\Application\Match\MatchFinderInterface;
use App\Application\MatchPerson\MatchPersonFinderInterface;
use App\Domain\Match\ValueObject\MatchId;
use App\Domain\Person\ValueObject\PersonId;
use App\Application\MatchPerson\MatchPersonModel;
use App\Application\Person\PersonFinderInterface;
use Doctrine\DBAL\Connection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DbalMatchPersonFinder implements MatchPersonFinderInterface
{
    private const TABLE_NAME = 'match_people';
    public function __construct(
        private readonly Connection $connection,
        private readonly MatchFinderInterface $matchFinder,
        private readonly PersonFinderInterface $personFinder
    ) {}

    public function getByIds(MatchId $matchId, PersonId $personId): MatchPersonModel
    {
        $query = $this->connection->createQueryBuilder();
        $query
            ->select('*')
            ->from(self::TABLE_NAME)
            ->where(
                'match_id = :match_id',
                'person_id = :person_id'
            )
            ->setParameters([
                'match_id' => (string) $matchId,
                'person_id' => (string) $personId
            ]);

        $result = $query->fetchAssociative();

        if (!$result) {
            throw new NotFoundHttpException('Match person not found');
        }

        return MatchPersonModel::createFromRow(
            $result,
            [
                MatchFinderInterface::class => $this->matchFinder,
                PersonFinderInterface::class => $this->personFinder
            ]
        );
    }

    public function getForMatch(MatchId $matchId): array
    {
        $query = $this->connection->createQueryBuilder();
        $query
            ->select('*')
            ->from(self::TABLE_NAME)
            ->where('match_id = :match_id')
            ->setParameter('match_id', (string) $matchId)
        ;
        $result = $query->fetchAllAssociative();
        $returnVal = [];

        foreach($result as $row) {
            $returnVal[] = MatchPersonModel::createFromRow(
                $row,
                [
                    MatchFinderInterface::class => $this->matchFinder,
                    PersonFinderInterface::class => $this->personFinder
                ]
            );
        }

        return $returnVal;
    }
}

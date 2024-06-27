<?php

declare(strict_types=1);

namespace App\Infrastructure\Match;

use App\Application\Match\MatchFinderInterface;
use App\Application\Match\MatchModel;
use App\Domain\Match\ValueObject\MatchId;
use Doctrine\DBAL\Connection;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DbalMatchFinder implements MatchFinderInterface
{
    private const TABLE_NAME = 'matches';

    public function __construct(
        private readonly Connection $connection,
        private readonly LoggerInterface $logger,
    ) {}

    public function getById(MatchId $id): MatchModel
    {
        $query = $this->connection->createQueryBuilder();
        $query
            ->select('*')
            ->from(self::TABLE_NAME)
            ->where('id = :id')
            ->setParameter('id', (string) $id)
        ;

        $result = $query->fetchAssociative();

        if (!$result) {
            throw new NotFoundHttpException('Match not found');
        }

        return MatchModel::createFromRow($result);
    }

    public function getAll(array $ids = []): array
    {
        $query = $this->connection->createQueryBuilder();
        $query
            ->select('*')
            ->from(self::TABLE_NAME)
            ->orderBy('id')
        ;

        foreach ($ids as $idIndex => $idValue) {
            $query
                ->orWhere("id = :id{$idIndex}")
                ->setParameter("id{$idIndex}", (string) $idValue)
            ;
        }

        $result = $query->fetchAllAssociative();

        $returnVal = [];

        foreach ($result as $row) {
            try {
                $returnVal[] = MatchModel::createFromRow($row);
            } catch (\Throwable $e) {
                $this->logger->error($e->getMessage());
            }
        }

        return $returnVal;
    }
}

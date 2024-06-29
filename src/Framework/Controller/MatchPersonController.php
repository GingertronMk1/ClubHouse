<?php

declare(strict_types=1);

namespace App\Framework\Controller;

use App\Application\Match\MatchFinderInterface;
use App\Application\MatchPerson\MatchPersonFinderInterface;
use App\Domain\Match\ValueObject\MatchId;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/match/{matchId}/person', name: 'match.person.')]
class MatchPersonController extends AbstractController
{
    #[Route(path: '/', name: 'index')]
    public function index(
        string $matchId,
        MatchFinderInterface $matchFinder,
        MatchPersonFinderInterface $matchPersonFinder
    ): Response
    {
        $matchId = MatchId::fromString($matchId);
        return $this->render('match-person/index.html.twig', [
            'match' => $matchFinder->getById($matchId),
            'people' => $matchPersonFinder->getForMatch($matchId)
        ]);
    }
}

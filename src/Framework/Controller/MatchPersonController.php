<?php

declare(strict_types=1);

namespace App\Framework\Controller;

use App\Application\MatchPerson\MatchPersonFinderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/match/{matchId}', name: 'match.player.')]
class MatchPersonController extends AbstractController
{
    #[Route(path: '/', name: 'index')]
    public function index(
        string $matchId,
        MatchPersonFinderInterface $matchPersonFinder
    ): Response
    {
        
    }
}

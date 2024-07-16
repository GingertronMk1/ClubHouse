<?php

namespace App\Framework\Controller\Api;

use App\Application\Person\PersonFinderInterface;
use App\Application\Sport\SportFinderInterface;
use App\Application\Team\TeamFinderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/api', name: 'api.')]
class ApiController extends AbstractController
{
    #[Route(path: '/', name: 'home')]
    public function home(
        SportFinderInterface $sportFinder,
        TeamFinderInterface $teamFinder,
        PersonFinderInterface $personFinder
    ): JsonResponse {
        return new JsonResponse(
            [
                'sports' => $sportFinder->getAll(),
                'teams' => $teamFinder->getAll(),
                'people' => $personFinder->getAll(),
            ]
        );
    }
}

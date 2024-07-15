<?php

namespace App\Framework\Controller\Api;

use App\Application\Sport\SportFinderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/api', name: 'api.')]
class ApiController extends AbstractController
{
    #[Route(path: '/', name: 'home')]
    public function home(
        SportFinderInterface $sportFinder
    ): JsonResponse {
        return new JsonResponse($sportFinder->getAll());
    }
}

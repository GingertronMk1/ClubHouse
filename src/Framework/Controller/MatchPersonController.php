<?php

declare(strict_types=1);

namespace App\Framework\Controller;

use App\Application\Match\MatchFinderInterface;
use App\Application\Match\MatchModel;
use App\Application\MatchPerson\Command\CreateMatchPersonCommand;
use App\Application\MatchPerson\CommandHandler\CreateMatchPersonCommandHandler;
use App\Application\MatchPerson\MatchPersonFinderInterface;
use App\Domain\Match\ValueObject\MatchId;
use App\Framework\Form\MatchPerson\CreateMatchPersonFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/match/{matchId}/person', name: 'match.person.')]
class MatchPersonController extends AbstractController
{
    public function __construct(
        private readonly MatchFinderInterface $matchFinder
    ) {}

    #[Route(path: '/', name: 'index')]
    public function index(
        MatchPersonFinderInterface $matchPersonFinder,
        string $matchId,
    ): Response {
        $match = $this->getMatch($matchId);

        return $this->render('match-person/index.html.twig', [
            'match' => $match,
            'people' => $matchPersonFinder->getForMatch($match->id),
        ]);
    }

    #[Route(path: '/create', name: 'create')]
    public function create(
        CreateMatchPersonCommandHandler $handler,
        Request $request,
        string $matchId
    ): Response {
        $match = $this->getMatch($matchId);

        $command = new CreateMatchPersonCommand($match);
        $form = $this->createForm(CreateMatchPersonFormType::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $handler->handle($command);

                return $this->redirectToRoute('match.person.index', ['matchId' => $matchId]);
            } catch (\Throwable $e) {
                throw new \Exception('Error creating person', previous: $e);
            }
        }

        return $this->render(
            'person/create.html.twig',
            [
                'form' => $form,
            ]
        );
    }

    private function getMatch(string $matchId): MatchModel
    {
        $matchId = MatchId::fromString($matchId);

        return $this->matchFinder->getById($matchId);
    }
}

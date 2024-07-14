<?php

declare(strict_types=1);

namespace App\Framework\Controller;

use App\Application\Match\Command\CreateMatchCommand;
use App\Application\Match\Command\UpdateMatchCommand;
use App\Application\Match\CommandHandler\CreateMatchCommandHandler;
use App\Application\Match\CommandHandler\UpdateMatchCommandHandler;
use App\Application\Match\MatchFinderInterface;
use App\Domain\Match\ValueObject\MatchId;
use App\Framework\Form\Match\CreateMatchFormType;
use App\Framework\Form\Match\UpdateMatchFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

#[Route(path: '/match', name: 'match.')]
class MatchController extends AbstractController
{
    #[Route(path: '/create', name: 'create')]
    public function create(
        CreateMatchCommandHandler $handler,
        Request $request
    ): Response {
        $command = new CreateMatchCommand();
        $form = $this->createForm(CreateMatchFormType::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $handler->handle($command);

                return $this->redirectToRoute('match.index');
            } catch (\Throwable $e) {
                throw new \Exception('Error creating person', previous: $e);
            }
        }

        return $this->render(
            'match/create.html.twig',
            [
                'form' => $form,
            ]
        );
    }

    #[Route(path: '/', name: 'index')]
    public function index(
        MatchFinderInterface $matchFinder
    ): Response {
        return $this->render('match/index.html.twig', [
            'matches' => $matchFinder->getAll(),
        ]);
    }

    #[Route(path: '/update/{matchId}', name: 'update', requirements: ['matchId' => Requirement::UUID_V7])]
    public function update(
        UpdateMatchCommandHandler $handler,
        MatchFinderInterface $matchFinder,
        Request $request,
        string $matchId,
    ): Response {
        $match = $matchFinder->getById(MatchId::fromString($matchId));
        $command = UpdateMatchCommand::fromModel($match);
        $form = $this->createForm(UpdateMatchFormType::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $handler->handle($command);

                return $this->redirectToRoute('match.index');
            } catch (\Throwable $e) {
                throw new \Exception('Error creating person', previous: $e);
            }
        }

        return $this->render(
            'match/create.html.twig',
            [
                'form' => $form,
            ]
        );
    }
}

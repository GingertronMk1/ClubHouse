<?php

declare(strict_types=1);

namespace App\Framework\Controller;

use App\Application\Sport\Command\CreateSportCommand;
use App\Application\Sport\CommandHandler\CreateSportCommandHandler;
use App\Application\Sport\CommandHandler\UpdateSportCommandHandler;
use App\Application\Sport\SportFinderInterface;
use App\Application\Team\Command\UpdateTeamCommand;
use App\Application\Team\CommandHandler\UpdateTeamCommandHandler;
use App\Domain\Sport\ValueObject\SportId;
use App\Framework\Form\Sport\CreateSportFormType;
use App\Framework\Form\Sport\UpdateSportFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/sport', name: 'sport.')]
class SportController extends AbstractController
{
    #[Route(path: '/create', name: 'create')]
    public function create(
        CreateSportCommandHandler $handler,
        Request $request
    ): Response
    {
        $command = new CreateSportCommand();
        $form = $this->createForm(CreateSportFormType::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $handler->handle($command);

                return $this->redirectToRoute('sport.index');
            } catch (\Throwable $e) {
                throw new \Exception('Error creating person', previous: $e);
            }
        }

        return $this->render(
            'sport/create.html.twig',
            [
                'form' => $form,
            ]
        );
    }

    #[Route(path: '/', name: 'index')]
    public function index(
        SportFinderInterface $sportFinder
    ): Response {
        return $this->render('sport/index.html.twig', [
            'sports' => $sportFinder->getAll(),
        ]);
    }

    #[Route(path: '/update/{sportId}', name: 'update')]
    public function update(
        UpdateSportCommandHandler $handler,
        SportFinderInterface $sportFinder,
        Request $request,
        string $sportId,
    ): Response {
        $team = $sportFinder->getById(SportId::fromString($sportId));
        $command = UpdateTeamCommand::fromTeam($sport);
        $form = $this->createForm(UpdateSportFormType::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $handler->handle($command);

                return $this->redirectToRoute('team.index');
            } catch (\Throwable $e) {
                throw new \Exception('Error creating person', previous: $e);
            }
        }

        return $this->render(
            'team/create.html.twig',
            [
                'form' => $form,
            ]
        );
    }

}

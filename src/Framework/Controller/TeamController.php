<?php

declare(strict_types=1);

namespace App\Framework\Controller;

use App\Application\Team\Command\CreateTeamCommand;
use App\Application\Team\CommandHandler\CreateTeamCommandHandler;
use App\Application\Team\TeamFinderInterface;
use App\Framework\Form\Team\CreateTeamFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/team', name: 'team.')]
class TeamController extends AbstractController
{
    #[Route(path: '/create', name: 'create')]
    public function create(
        CreateTeamCommandHandler $handler,
        Request $request
    ): Response {
        $command = new CreateTeamCommand();
        $form = $this->createForm(CreateTeamFormType::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $handler->handle($command);

                return $this->redirectToRoute('person.index');
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

    #[Route(path: '/', name: 'index')]
    public function index(
        TeamFinderInterface $teamFinder
    ): Response {
        return $this->render('team/index.html.twig', [
        ]);
    }
}

<?php

declare(strict_types=1);

namespace App\Framework\Controller;

use App\Application\Team\Command\CreateTeamCommand;
use App\Application\Team\CommandHandler\CreateTeamCommandHandler;
use App\Framework\Form\Team\CreateTeamFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/team', name: 'person.')]
class TeamController extends AbstractController
{
    public function create(
        CreateTeamCommandHandler $handler,
        Request $request
    ): Response
    {
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
}

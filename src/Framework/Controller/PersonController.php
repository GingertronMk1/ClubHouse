<?php

declare(strict_types=1);

namespace App\Framework\Controller;

use App\Application\Person\Command\CreatePersonCommand;
use App\Application\Person\CommandHandler\CreatePersonCommandHandler;
use App\Framework\Form\Person\CreatePersonFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PersonController extends AbstractController
{
    public function __construct(
    ) {}

    #[Route(path: '/register', name: 'app_register')]
    public function create(
        CreatePersonCommandHandler $handler,
        Request $request,
    ): Response {
        $command = new CreatePersonCommand();
        $form = $this->createForm(CreatePersonFormType::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $handler->handle($command);

                return $this->redirectToRoute('home');
            } catch (\Throwable $e) {
                throw new \Exception('Error creating user', previous: $e);
            }
        }

        return $this->render(
            'security/register.html.twig',
            [
                'form' => $form,
            ]
        );
    }
}

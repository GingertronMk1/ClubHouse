<?php

namespace App\Framework\Controller;

use App\Application\User\Command\CreateUserCommand;
use App\Application\User\CommandHandler\CreateUserCommandHandler;
use App\Form\CreateUserFormType;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Throwable;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route(path:'/register', name: 'app_register')]
    public function register(CreateUserCommandHandler $handler, Request $request): Response {
        $command = new CreateUserCommand();
        $form = $this->createForm(CreateUserFormType::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $handler->handle($command);
                return $this->redirectToRoute('home');
            } catch (Throwable $e) {
                throw new Exception("Error creating user", previous: $e);
            }
        }

        return $this->render(
            'security/register.html.twig',
            [
                'form' => $form
            ]
        );
    }
}

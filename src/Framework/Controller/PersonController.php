<?php

declare(strict_types=1);

namespace App\Framework\Controller;

use App\Application\Person\Command\CreatePersonCommand;
use App\Application\Person\Command\UpdatePersonCommand;
use App\Application\Person\CommandHandler\CreatePersonCommandHandler;
use App\Application\Person\CommandHandler\UpdatePersonCommandHandler;
use App\Application\Person\PersonFinderInterface;
use App\Domain\Person\ValueObject\PersonId;
use App\Framework\Form\Person\CreatePersonFormType;
use App\Framework\Form\Person\UpdatePersonFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/person', name: 'person.')]
class PersonController extends AbstractController
{
    public function __construct(
    ) {}

    #[Route(path: '/create', name: 'create')]
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

                return $this->redirectToRoute('person.index');
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

    #[Route(path: '/', name: 'index')]
    public function index(
        PersonFinderInterface $personFinder
    ): Response {
        return $this->render('person/index.html.twig', [
            'people' => $personFinder->getAll(),
        ]);
    }

    #[Route('/update/{personId}', name: 'update')]
    public function update(
        UpdatePersonCommandHandler $handler,
        Request $request,
        PersonFinderInterface $personFinder,
        string $personId
    ): Response {
        $person = $personFinder->getById(PersonId::fromString($personId));

        $command = UpdatePersonCommand::fromPerson($person);
        $form = $this->createForm(UpdatePersonFormType::class, $command);
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
            'person/create.html.twig',
            [
                'form' => $form,
            ]
        );
    }
}

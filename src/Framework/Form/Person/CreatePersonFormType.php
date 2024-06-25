<?php

declare(strict_types=1);

namespace App\Framework\Form\Person;

use App\Application\User\UserFinderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreatePersonFormType extends AbstractType
{
    public function __construct(
        private readonly UserFinderInterface $userFinder
    ) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add(
                'user',
                ChoiceType::class,
                [
                    'placeholder' => 'Optionally assign this Person to a User',
                    'required' => false,
                    'choices' => $this->userFinder->getAll(),
                    'choice_value' => 'id',
                    'choice_label' => 'email'
                ]
            )
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}

<?php

declare(strict_types=1);

namespace App\Framework\Form\Team;

use App\Application\Person\PersonFinderInterface;
use App\Application\Sport\SportFinderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class CreateTeamFormType extends AbstractType
{
    public function __construct(
        private readonly PersonFinderInterface $personFinder,
        private readonly SportFinderInterface $sportFinder,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add(
                'description',
                TextareaType::class,
                [
                    'required' => false,
                ]
            )
            ->add(
                'sport',
                ChoiceType::class,
                [
                    'choices' => $this->sportFinder->getAll(),
                    'choice_value' => 'id',
                    'choice_label' => 'name',
                ]
            )
            ->add(
                'people',
                ChoiceType::class,
                [
                    'multiple' => true,
                    'required' => false,
                    'choices' => $this->personFinder->getAll(),
                    'choice_value' => 'id',
                    'choice_label' => 'name',
                ]
            )
            ->add('submit', SubmitType::class)
        ;
    }
}

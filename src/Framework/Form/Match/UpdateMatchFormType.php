<?php

declare(strict_types=1);

namespace App\Framework\Form\Match;

use App\Application\Sport\SportFinderInterface;
use App\Application\Team\TeamFinderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class UpdateMatchFormType extends AbstractType
{
    public function __construct(
        private readonly TeamFinderInterface $teamFinder,
        private readonly SportFinderInterface $sportFinder
    ) {}

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('details', TextareaType::class, ['required' => false])
            ->add('start', DateTimeType::class)
            ->add(
                'team1',
                ChoiceType::class,
                [
                    'placeholder' => 'Who was the home side?',
                    'required' => false,
                    'choices' => $this->teamFinder->getAll(),
                    'choice_value' => 'id',
                    'choice_label' => 'name',
                ]
            )
            ->add(
                'team2',
                ChoiceType::class,
                [
                    'placeholder' => 'Who was the away side?',
                    'required' => false,
                    'choices' => $this->teamFinder->getAll(),
                    'choice_value' => 'id',
                    'choice_label' => 'name',
                ]
            )
            ->add(
                'sport',
                ChoiceType::class,
                [
                    'placeholder' => 'What sport was it?',
                    'required' => false,
                    'choices' => $this->sportFinder->getAll(),
                    'choice_value' => 'id',
                    'choice_label' => 'name',
                ]
            )
            ->add(
                'submit',
                SubmitType::class
            )
        ;
    }
}

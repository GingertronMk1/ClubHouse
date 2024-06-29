<?php

declare(strict_types=1);

namespace App\Framework\Form\MatchPerson;

use App\Application\Match\MatchFinderInterface;
use App\Application\Person\PersonFinderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class CreateMatchPersonFormType extends AbstractType
{
    public function __construct(
        private readonly PersonFinderInterface $personFinder,
        private readonly MatchFinderInterface $matchFinder
    ) {}

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'person',
                ChoiceType::class,
                [
                    'placeholder' => 'Choose the person',
                    'choices' => $this->personFinder->getAll(),
                    'choice_value' => 'id',
                    'choice_label' => 'name',
                ]
            )->add(
                'match',
                ChoiceType::class,
                [
                    'placeholder' => 'Choose the person',
                    'choices' => $this->matchFinder->getAll(),
                    'choice_value' => 'id',
                    'choice_label' => 'name',
                ]
            )
            ->add(
                'role',
                TextType::class,
                [
                    'required' => false,
                ]
            )
            ->add('submit', SubmitType::class)
        ;
    }
}

<?php

declare(strict_types=1);

namespace App\Framework\Form\MatchPerson;

use App\Application\Match\MatchFinderInterface;
use App\Application\Match\MatchModel;
use App\Application\Person\PersonFinderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateMatchPersonFormType extends AbstractType
{
    public function __construct(
        private readonly PersonFinderInterface $personFinder,
        private readonly MatchFinderInterface $matchFinder
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $match = $options['match'];
        if (MatchModel::class !== get_class($match)) {
            throw new \Exception();
        }

        $team1 = $match->team1;
        $team2 = $match->team2;

        $people = [];
        if (!is_null($team1)) {
            $people[$team1->name] = $this->personFinder->getForTeam($team1->id);
        }

        if (!is_null($team2)) {
            $people[$team2->name] = $this->personFinder->getForTeam($team2->id);
        }

        $builder
            ->add(
                'person',
                ChoiceType::class,
                [
                    'placeholder' => 'Choose the person',
                    'choices' => $people,
                    'choice_value' => 'id',
                    'choice_label' => 'name',
                ]
            )->add(
                'role',
                TextType::class,
                [
                    'required' => false,
                ]
            )
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'match' => null,
        ]);
    }
}

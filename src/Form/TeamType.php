<?php

namespace App\Form;

use App\Entity\Teams;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TeamType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true
            ])
            ->add('division', ChoiceType::class, [
                'label' => 'Division',
                'choices' => [
                    'Nationale 1A' => 'Nationale 1A',
                    'Nationale 1B' => 'Nationale 1B',
                    'Nationale 2A' => 'Nationale 2A',
                    'Nationale 2B' => 'Nationale 2B',
                    'Nationale 2C' => 'Nationale 2C',
                    'Régionale A' => 'Régionale A',
                    'Régionale B' => 'Régionale B',
                    'Régionale C' => 'Régionale C',
                    'Provinciale 1A' => 'Provinciale 1A',
                    'Provinciale 1B' => 'Provinciale 1B',
                    'Provinciale 2A' => 'Provinciale 2A',
                    'Provinciale 2B' => 'Provinciale 2B',
                    'Provinciale 3A' => 'Provinciale 3A',
                    'Provinciale 3B' => 'Provinciale 3B',
                ],
                'required' => true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Teams::class,
        ]);
    }
}

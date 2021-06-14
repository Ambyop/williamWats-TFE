<?php

namespace App\Form;

use App\Entity\ClubLists;
use App\Entity\MatchList;
use App\Entity\Teams;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MatchType extends AbstractType
{
    /**
     * @throws \Exception
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $minimumDate = (new \DateTime('+1 days', new \DateTimeZone('Europe/Brussels')))->format('Y-m-d');
        $maximumDate = (new \DateTime('+2 years', new \DateTimeZone('Europe/Brussels')))->format('Y-m-d');

        $builder
            ->add('date', DateTimeType::class, [
                'label' => 'Date et Heure du match',
                'input_format' => 'Y-m-d H:i:s',
                'widget' => 'single_text',
                'required' => true,
                'attr' => array(
                    'min' => $minimumDate,
                    'max' => $maximumDate,
                )
            ])
            ->add('location', EntityType::class, [
                'class' => ClubLists::class,
                'label' => 'Lieu du match',
                'choice_label' => 'name'
            ])
            ->add('type', ChoiceType::class, [
                'label' => 'Catégorie de match',
                'multiple' => false,
                'expanded' => false,
                'choices' => [
                    "Interclub" => "INTERCLUB",
                    "Division" => "DIVISION",
                    "Amical" => "AMICAL",
                ],
                'required' => true
            ])
            ->add('team', EntityType::class, [
                'class' => Teams::class,
                'label' => 'Equipe',
                'choice_label' => 'name'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MatchList::class,
        ]);
    }
}

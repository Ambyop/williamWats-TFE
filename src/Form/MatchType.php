<?php

namespace App\Form;

use App\Entity\MatchList;
use App\Entity\Teams;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
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
        $now = ( new \DateTime('+2 years', new \DateTimeZone('Europe/Brussels')))->format('Y-m-d');
        $minimumDate = ( new \DateTime('now', new \DateTimeZone('Europe/Brussels')))->format('Y-m-d');

        $builder
            ->add('date', DateType::class, [
                'label' => 'Quand êtes-vous né?',
                'widget' => 'single_text',
                'required' => true,
                'attr' => array(
                    'max' => $now,
                    'min' => $minimumDate,
                )
            ])
            ->add('location',TextType::class, [
                'label' => 'Lieu du match'
            ])
            ->add('type', ChoiceType::class, [
                'label' => 'Catégorie de match',
                'multiple' => false,
                'expanded' => false,
                'choices' => [
                    "Interclub" => "INTERCLUB",
                    "Division" => "DIVISION",
                    "AMICAL" => "AMICAL",
                ],
                'required' => true
            ])
            ->add('team', EntityType::class, [
                'class' => Teams::class,
                'choice_label'=>'name'
            ])
//            ->add('user', EntityType::class, [
//                'class' => User::class,
//                'choice_label'=>'User'
//            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MatchList::class,
        ]);
    }
}

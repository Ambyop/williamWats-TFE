<?php

namespace App\Form;

use App\Entity\Stage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StageType extends AbstractType
{
    /**
     * @throws \Exception
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $now = (new \DateTime('now', new \DateTimeZone('Europe/Brussels')))->format('Y-m-d');
        $oneWeek = (new \DateTime('+1 week', new \DateTimeZone('Europe/Brussels')))->format('Y-m-d');
        $maximumDate = (new \DateTime('+5 years', new \DateTimeZone('Europe/Brussels')))->format('Y-m-d');
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du stage',
                'required' => true
            ])
            ->add('beginAt', DateType::class, [
                'label' => 'Début du stage',
                'widget' => 'single_text',
                'required' => false,
                'attr' => array(
                    'max' => $maximumDate,
                    'min' => $now,
                )
            ])
            ->add('EndAt', DateType::class, [
                'label' => 'Fin du stage',
                'widget' => 'single_text',
                'required' => false,
                'attr' => array(
                    'max' => $maximumDate,
                    'min' => $oneWeek,
                )
            ])
            ->add('price', MoneyType::class, [
                'label' => 'Prix',
                'required' => true
            ])
            ->add('startHours', TimeType::class, [
                'label' => 'Heure de début',
                'required' => true,
                'input'  => 'timestamp',
                'widget' => 'choice',
            ])
            ->add('EndHours', TimeType::class, [
                'label' => 'Heure de fin',
                'required' => true,
                'input' => 'timestamp',
                'widget' => 'choice'
            ])
            ->add('days', ChoiceType::class, [
                'label' => 'Jours de stages',
                'placeholder' => 'Choisissez les jours où se dérouleront le stage',
                'multiple' => true,
                'expanded' => true,
                'choices' => ['Lundi' => 'Mo',
                    'Mardi' => 'Tu',
                    'Mercredi' => 'We',
                    'Jeudi' => 'Th',
                    'Vendredi' => 'Fr',
                    'Samedi' => 'Sa',
                    'Dimanche' => 'Su'
                ]
            ])
            ->add('personNumber', IntegerType::class, [
                'label' => 'Nombre de places'
            ])
            ->add('isDisabled', ChoiceType::class, [
                'label' => 'Disponibilité',
                'choices' => ['Disponible' => 0, 'Non-disponible' => 1]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Stage::class,
        ]);
    }
}

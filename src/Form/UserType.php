<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email de l\'utilisateur',
                'required' => true
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom de l\'utilisateur',
                'required' => true
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom de l\'utilisateur',
                'required' => true
            ])
            ->add('phoneNumber', TelType::class, [
                'label' => 'Numéro de téléphone',
                'required' => false
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe ne sont pas identiques',
                'options' => [
                    'attr' => ['class' => 'password-field'
                    ]],
                'required' => true,
                'first_options' => [
                    'label' => 'Mot de passe'
                ],
                'second_options' => [
                    'label' => 'Confirmation du mot de passe'
                ],
            ])
            ->add('roles', ChoiceType::class, [
                'label' => 'Rôle(s)',
                'multiple' => true,
                'expanded' => true,
                'choices' => [
                    "Utilisateur" => "ROLE_USER",
                    "Joueur" => "ROLE_PLAYER",
                    "Entraîneur" => "ROLE_COACH",
                    "Administrateur" => "ROLE_ADMIN"
                ],
                'required' => true
            ])
            ->add('ranking', ChoiceType::class, [
                'label' => 'Classement',
                'choices' => [
                    'NC' => 'Non-Classé',
                    'E6' => 'E6',
                    'E5' => 'E5',
                    'E4' => 'E4',
                    'E3' => 'E3',
                    'E2' => 'E2',
                    'E1' => 'E1',
                    'E0' => 'E0',
                    'D6' => 'D6',
                    'D5' => 'D5',
                    'D4' => 'D4',
                    'D3' => 'D3',
                    'D2' => 'D2',
                    'D1' => 'D1',
                    'D0' => 'D0',
                    'C6' => 'C6',
                    'C5' => 'C5',
                    'C4' => 'C4',
                    'C3' => 'C3',
                    'C2' => 'C2',
                    'C1' => 'C1',
                    'C0' => 'C0',
                    'B6' => 'B6',
                    'B5' => 'B5',
                    'B4' => 'B4',
                    'B3' => 'B3',
                    'B2' => 'B2',
                    'B1' => 'B1',
                    'B0' => 'B0',
                    'A20' => 'A20',
                    'A19' => 'A19',
                    'A18' => 'A18',
                    'A17' => 'A17',
                    'A16' => 'A16',
                    'A15' => 'A15',
                    'A14' => 'A14',
                    'A13' => 'A13',
                    'A12' => 'A12',
                    'A11' => 'A11',
                    'A10' => 'A10',
                    'A9' => 'A9',
                    'A8' => 'A8',
                    'A7' => 'A7',
                    'A6' => 'A6',
                    'A5' => 'A5',
                    'A4' => 'A4',
                    'A3' => 'A3',
                    'A2' => 'A2',
                    'A1' => 'A1',
                ],
                'required' => true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

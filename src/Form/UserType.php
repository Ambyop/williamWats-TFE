<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
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
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe ne sont pas identiques',
                'options' => [
                    'attr' => ['class' => 'password-field'
                    ]],
                'required' => true,
                'first_options'  => [
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
            ->add('Ranking')
//            ->add('team')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

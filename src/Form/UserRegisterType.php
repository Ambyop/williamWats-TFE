<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserRegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'Prénom de l\'utilisateur',
                'required' => true
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom de l\'utilisateur',
                'required' => true
            ])
            ->add('pseudo', TextType::class, [
                'label' => 'Votre identifiant',
                'required' => true
            ])
            ->add('birthday', BirthdayType::class, [
                'label' => 'Quand êtes-vous né?',
                'format' => 'd/MM/yyyy',
                'years' => range(date('Y')-80, date('Y') - 5),
                'required' => true,
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email de l\'utilisateur',
                'required' => true
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe',
                'required' => true,

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class UserEditType extends AbstractType
{
    /**
     * @throws \Exception
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $now = (new \DateTime('now', new \DateTimeZone('Europe/Brussels')))->format('Y-m-d');
        $minimumDate = (new \DateTime('-70 years', new \DateTimeZone('Europe/Brussels')))->format('Y-m-d');

        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email de l\'utilisateur',
                'required' => true
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom de l\'utilisateur',
                'required' => true
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom de l\'utilisateur'
            ])
            ->add('phoneNumber', TelType::class, [
                'label' => 'Numéro de téléphone',
                'required' => false
            ])
            ->add('birthday', BirthdayType::class, [
                'label' => 'Quand êtes-vous né?',
                'widget' => 'single_text',
                'required' => true,
                'attr' => array(
                    'max' => $now,
                    'min' => $minimumDate,
                )
            ])
            ->add('imageFile', VichImageType::class, [
                'label' => 'Image de profil',
                'required' => false,
                'allow_delete' => true,
                'download_uri' => false,
                'image_uri' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

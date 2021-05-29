<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserEditType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/profile", name="user_private_profile")
     * @param UserRepository $userRepository
     * @param TokenStorageInterface $tokenStorage
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param EncoderFactoryInterface $encoderFactory
     * @param UserPasswordEncoderInterface $encoder
     * @return Response
     * @throws \Exception
     */
    public function profile(UserRepository $userRepository, TokenStorageInterface $tokenStorage, Request $request, EntityManagerInterface $manager, EncoderFactoryInterface $encoderFactory, UserPasswordEncoderInterface $encoder): Response
    {
        // load user Data
        $user = $userRepository->find($tokenStorage->getToken()->getUser());

        // generate Edit Profile Form
        $formProfile = $this->createForm(UserEditType::class, $user);
        $formProfile->handleRequest($request);
        if ($formProfile->isSubmitted() && $formProfile->isValid()) {
            $now = new \DateTime('now', new \DateTimeZone('Europe/Brussels'));
            $user->setUpdatedAt($now);
            $manager->persist($user);
            $manager->flush();
            $this->addFlash('success', 'Votre profil a bien été modifié');
            return $this->redirectToRoute('user_private_profile');
        }

        // generate Edit Password Form
        $formPassword = $this->createFormBuilder()
            ->add('oldPassword', PasswordType::class, [
                'label' => 'Ancien Mot de passe',
                'invalid_message' => 'Mot de passe incorrect',
            ])
            ->add('newPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe ne correspondent pas',
                'options' => [
                    'attr' => ['class' => 'password-field'
                    ]],
                'required' => true,
                'first_options' => [
                    'label' => 'Nouveau mot de passe'
                ],
                'second_options' => [
                    'label' => 'Confirmation du nouveau mot de passe'
                ],
            ])
            ->getForm();
        $formPassword->handleRequest($request);
        if ($formPassword->isSubmitted() && $formPassword->isValid()) {
            $now = new \DateTime('now', new \DateTimeZone('Europe/Brussels'));
            $encoderF = $encoderFactory->getEncoder($user);
            if ($encoderF->isPasswordValid($user->getPassword(), $formPassword->get('oldPassword')->getData(), $user->getSalt())) {
                $user->setPassword($encoder->encodePassword($user, $formPassword->get('newPassword')->getData()));
                $user->setUpdatedAt($now);
                $manager->persist($user);
                $manager->flush();
                $this->addFlash('succes', 'Mot de passe modifié.');
            } else {
                $this->addFlash('warning', 'Mot de passe incorrect.');
                $formPassword->get('oldPassword')->addError(new FormError("Mot de passe incorrect."));
            }
        }

        return $this->render('user/private_profile.html.twig', [
            'user' => $user,
            'editProfileForm' => $formProfile->createView(),
            'editPasswordForm' => $formPassword->createView(),
        ]);
    }

    /**
     * @Route("/user/{id}", name="user_public_profile")
     * @param User $user
     * @param UserRepository $userRepository
     * @param TokenStorageInterface $tokenStorage
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     * @throws \Exception
     */
    public function user(User $user, UserRepository $userRepository, TokenStorageInterface $tokenStorage, Request $request, EntityManagerInterface $manager): Response
    {
        // load user Data
        $user = $userRepository->find($user);

        return $this->render('user/public_profile.html.twig', [
            'user' => $user,
        ]);
    }
}

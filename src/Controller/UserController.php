<?php

namespace App\Controller;

use App\Entity\MatchCancellation;
use App\Entity\MatchList;
use App\Form\MatchCancellationType;
use App\Form\UserEditType;
use App\Repository\MatchListRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use EWZ\Bundle\RecaptchaBundle\Form\Type\EWZRecaptchaType;
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
     * @Route("/profil", name="user_private_profile")
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
            ->add('recaptcha', EWZRecaptchaType::class, [
                'label' => ' ',
                'language' => 'fr'
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
                $this->addFlash('success', 'Mot de passe modifié.');
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
//
//    /**
//     * @Route("/user/{id}", name="user_public_profile")
//     * @param User $user
//     * @param UserRepository $userRepository
//     * @param TokenStorageInterface $tokenStorage
//     * @param Request $request
//     * @param EntityManagerInterface $manager
//     * @return Response
//     * @throws \Exception
//     */
//    public function user(User $user, UserRepository $userRepository, TokenStorageInterface $tokenStorage, Request $request, EntityManagerInterface $manager): Response
//    {
//        // load user Data
//        $user = $userRepository->find($user);
//
//        return $this->render('user/public_profile.html.twig', [
//            'user' => $user,
//        ]);
//    }
//
    /**
     * @Route("/profil/equipe", name="user_team")
     * @param UserRepository $userRepository
     * @param TokenStorageInterface $tokenStorage
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function team(UserRepository $userRepository, TokenStorageInterface $tokenStorage, Request $request, EntityManagerInterface $manager): Response
    {
        // load user Data
        $user = $userRepository->find($tokenStorage->getToken()->getUser());
        $team = $user->getTeam();
        $partners = $userRepository->findBy([
            'team' => $team->getId()
        ]);

        return $this->render('user/user_teams.html.twig', [
            'user' => $user,
            'team' => $team,
            'partners' => $partners,
        ]);
    }

    /**
     * @Route("/profil/matchs", name="user_match")
     * @param UserRepository $userRepository
     * @param MatchListRepository $matchListRepository
     * @param TokenStorageInterface $tokenStorage
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     * @throws \Exception
     */
    public function matchs(UserRepository $userRepository, MatchListRepository $matchListRepository, TokenStorageInterface $tokenStorage, Request $request, EntityManagerInterface $manager): Response
    {
        // load user Data
        $now = new \DateTime('now', new \DateTimeZone('Europe/Brussels'));
        $user = $userRepository->find($tokenStorage->getToken()->getUser());
        $allSubscribedMatchs = $user->getMatchLists()->toArray();
        $subscribedMatchs = [];
        foreach ( $allSubscribedMatchs as $match) {
            if ( $match->getDate() > $now)
            {
                array_push($subscribedMatchs,$match);
            }
        }

        $unScribedMatchs =$matchListRepository->findByTeamWithExpirationDate($user->getTeam(),$now);
        foreach ($unScribedMatchs as $key => $match) {
            foreach ($subscribedMatchs as $subscribedMatch) {
                if ($match->getId() == $subscribedMatch->getId()) {
                    unset($unScribedMatchs[$key]);
                }
            }
        }

        // create cancellation match form
        $cancellation = new MatchCancellation();
        $form = $this->createForm(MatchCancellationType::class, $cancellation);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $now = new \DateTime('now', new \DateTimeZone('Europe/Brussels'));
            $cancellation->setMatchs($matchListRepository->find(1))
                ->setCreatedAt($now)
                ->setUser($user);

            $manager->persist($cancellation);
            $manager->flush();

//            $this->addFlash('success', 'L\'utilisateur ' . $user->getEmail() . ' a bien été créé.');
            return $this->redirectToRoute('user_match');
        }

        return $this->render('user/user_matchs.html.twig', [
            'user' => $user,
            'subscribedMatchs' => $subscribedMatchs,
            'unSubscribedMatchs' => $unScribedMatchs,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/profil/matchs/inscription/{match}", name="subscribe_match")
     * @param MatchList $match
     * @param UserRepository $userRepository
     * @param TokenStorageInterface $tokenStorage
     * @param EntityManagerInterface $manager
     * @return Response
     * @throws \Exception
     */
    public function subscribeMatch(MatchList $match, UserRepository $userRepository, TokenStorageInterface $tokenStorage, EntityManagerInterface $manager): Response
    {
        // load user Data
        $user = $userRepository->find($tokenStorage->getToken()->getUser());
        if ($user->getTeam() === $match->getTeam()) {
            $now = new \DateTime('now', new \DateTimeZone('Europe/Brussels'));
            $match->addUser($user);
            $match->setUpdatedAt($now);
            $user->setUpdatedAt($now);
            $manager->flush();
            $this->addFlash('success', 'Vous vous êtes inscrit au match du ' . date_format($match->getDate(), "d F Y") . ' à ' . $match->getLocation() . '.');
        } else {
            $this->addFlash('danger', 'Vous ne pouvez pas vous inscrire au match d\'une autre équipe.');
        }
        return $this->redirectToRoute('user_match');
    }

    /**
     * @Route("/profil/matchs/desinscription/{match}", name="unsubscribe_match")
     * @param MatchList $match
     * @param UserRepository $userRepository
     * @param TokenStorageInterface $tokenStorage
     * @param EntityManagerInterface $manager
     * @return Response
     * @throws \Exception
     */
    public function unSubscribeMatch(MatchList $match, UserRepository $userRepository, TokenStorageInterface $tokenStorage, EntityManagerInterface $manager): Response
    {
        // load user Data
        $user = $userRepository->find($tokenStorage->getToken()->getUser());
        if ($user->getTeam() === $match->getTeam()) {
            $now = new \DateTime('now', new \DateTimeZone('Europe/Brussels'));
            $match->removeUser($user);
            $match->setUpdatedAt($now);
            $user->setUpdatedAt($now);
            $manager->flush();
            $this->addFlash('warning', 'Vous n\'êtes plus inscrit au match du ' . date_format($match->getDate(), "d F Y") . ' à ' . $match->getLocation() . '.');
        } else {
            $this->addFlash('danger', 'Vous ne pouvez pas faire ça au match d\'une autre équipe.');
        }
        return $this->redirectToRoute('user_match');
    }
}

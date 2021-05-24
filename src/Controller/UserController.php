<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserEditType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/profile", name="user_private_profile")
     * @param UserRepository $userRepository
     * @param TokenStorageInterface $tokenStorage
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     * @throws \Exception
     */
    public function profile(UserRepository $userRepository, TokenStorageInterface $tokenStorage, Request $request, EntityManagerInterface $manager): Response
    {
        // load user Data
        $user = $userRepository->find($tokenStorage->getToken()->getUser());

        // generate Edit Profile Form
        $formProfile = $this->createForm(UserEditType::class,$user);
        $formProfile->handleRequest($request);
        if ($formProfile->isSubmitted() && $formProfile->isValid()) {
            $now = new \DateTime('now', new \DateTimeZone('Europe/Brussels'));
            $user->setUpdatedAt($now);
            $manager->persist($user);
            $manager->flush();
            $this->addFlash('success', 'Votre profil a bien été modifié');
            return $this->redirectToRoute('user_private_profile');
        }
        return $this->render('user/private_profile.html.twig', [
            'user' => $user,
            'editProfileForm' => $formProfile->createView(),
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
    public function user(User $user,UserRepository $userRepository, TokenStorageInterface $tokenStorage, Request $request, EntityManagerInterface $manager): Response
    {
        // load user Data
        $user = $userRepository->find($user);

        return $this->render('user/public_profile.html.twig', [
            'user' => $user,
        ]);
    }
}

<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/profile", name="user_profile")
     * @param UserRepository $userRepository
     * @param TokenStorageInterface $tokenStorage
     * @return Response
     */
    public function profile(UserRepository $userRepository, TokenStorageInterface $tokenStorage): Response
    {
        // load user Data
        $user = $userRepository->find($tokenStorage->getToken()->getUser());

        // generate Edit Profile Form
        return $this->render('user/profile.html.twig', [
            'user' => $user,
        ]);
    }
}

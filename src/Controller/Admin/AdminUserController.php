<?php

namespace App\Controller\Admin;

use App\Repository\TeamsRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminUserController extends AbstractController
{
    /**
     * @Route("/admin/user", name="admin_user")
     * @param UserRepository $userRepository
     * @param TeamsRepository $teamsRepository
     * @return Response
     */
    public function users(UserRepository $userRepository, TeamsRepository $teamsRepository): Response
    {
        $users = $userRepository->findAll();
        return $this->render('admin/admin-user.html.twig',
            [
                'users' => $users,
            ]);
    }
}

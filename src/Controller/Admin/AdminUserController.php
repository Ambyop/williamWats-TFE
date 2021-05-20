<?php

namespace App\Controller\Admin;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminUserController extends AbstractController
{
    /**
     * @Route("/admin/user", name="admin_user")
     * @param UserRepository $repository
     * @return Response
     */
    public function users(UserRepository $repository): Response
    {
        return $this->render('admin/admin-user.html.twig',
            [
                'users' => $repository->findAll()
            ]);
    }
}

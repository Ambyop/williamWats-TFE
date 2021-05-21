<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Repository\TeamsRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminUserController extends AbstractController
{
    /**
     * @Route("/admin/user", name="admin_user")
     * @param UserRepository $userRepository
     * @return Response
     */
    public function users(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();
        return $this->render('admin/admin_user.html.twig',
            [
                'users' => $users,
            ]);
    }

    /**
     * @Route("/admin/promote/{id}/{role}",name="admin_user_rank")
     * @param User $user
     * @param EntityManagerInterface $manager
     * @param $role
     * @return Response
     */
    public function promoteUser(User $user, EntityManagerInterface $manager,$role):Response
    {
        $now = new \DateTime('now', new \DateTimeZone('Europe/Brussels'));
        $user->setRoles([$role]);
        $user->setUpdatedAt($now);
        $manager->flush();

        return $this->redirectToRoute('admin_user');
    }
}

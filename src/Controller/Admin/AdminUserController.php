<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\TeamsRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
    public function promoteUser(User $user, EntityManagerInterface $manager, $role): Response
    {
        $now = new \DateTime('now', new \DateTimeZone('Europe/Brussels'));
        $user->setRoles([$role]);
        $user->setUpdatedAt($now);
        $manager->flush();

        return $this->redirectToRoute('admin_user');
    }

    /**
     * @Route("/admin/user/create", name="admin_user_add")
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @return Response
     * @throws \Exception
     */
    public function addUser(EntityManagerInterface $manager, Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $now = new \DateTime('now', new \DateTimeZone('Europe/Brussels'));
            $user->setCreatedAt($now)
                ->setLastLogAt($now)
                ->setUpdatedAt($now);
            $user->setPassword($encoder->encodePassword($user, $user->getPassword()));

            $manager->persist($user);
            $manager->flush();
            $this->addFlash('success', 'L\'utilisateur ' . $user->getEmail() . ' a bien été créé.');

            return $this->redirectToRoute('admin_user');
        }

        return $this->render("admin/create_user.html.twig", [
            'form' => $form->createView()
        ]);
    }
}

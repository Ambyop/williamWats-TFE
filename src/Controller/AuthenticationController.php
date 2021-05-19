<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserRegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AuthenticationController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @return Response
     * @throws \Exception
     */
    public function register(EntityManagerInterface $manager, Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $user = new User();
        $form = $this->createForm(UserRegisterType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $now = new \DateTime('now', new \DateTimeZone('Europe/Brussels'));
            $user->setCreatedAt($now)
                ->setRoles("USER")
                ->setCreatedAt($now)
                ->setUpdatedAt($now)
                ->setLastLogAt($now)
                ->setRanking('')
            ;
            $user->setPassword($encoder->encodePassword($user, $user->getPassword()));

            $manager->persist($user);
            $manager->flush();
            $this->addFlash('success', 'L\'utilisateur ' . $user->getFirstname() . ' ' . $user->getLastname() . ' a bien été créé.');

            return $this->redirectToRoute('homepage');
        }

        return $this->render("authentication/register.html.twig", [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('authentication/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {
        throw new \LogicException();
    }
}

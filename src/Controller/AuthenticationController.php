<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Service\EmailType;
use App\Entity\User;
use App\Form\UserRegisterType;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AuthenticationController extends AbstractController
{
    private EmailController $emailController;

    public function __construct(EmailController $emailController)
    {
        $this->emailController = $emailController;
    }

    /**
     * @Route("/register", name="register")
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
                ->setRoles(["ROLE_USER"])
                ->setCreatedAt($now)
                ->setUpdatedAt($now)
                ->setLastLogAt($now)
                ->setIsDisabled(true)
                ->setRanking('')
                ->setToken(bin2hex(random_bytes(20)))
                ->setTokenValidity(new \DateTime('+15 minutes', new \DateTimeZone('Europe/Brussels')));
            $user->setPassword($encoder->encodePassword($user, $user->getPassword()));

            $this->sendSignUpConfirmationEmail($user);

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
     * @Route("/verif-account/{token}", name="verif_account")
     * @param $token
     * @param UserRepository $userRepository
     * @param EntityManagerInterface $manager
     * @return Response
     * @throws \Exception
     */
    public function accountVerification($token,UserRepository $userRepository, EntityManagerInterface $manager): Response
    {
        $user = $userRepository->findOneBy([
            'token' => $token
        ]);

        if (!isset($user)) {
            $this->addFlash('warning', 'Cette adresse est invalide');
            return $this->redirectToRoute('homepage');
        }

        $now = date_format(new \DateTime('now', new \DateTimeZone('Europe/Brussels')),"Y/m/d H:i:s");
        $expireToken = date_format($user->getTokenValidity(),"Y/m/d H:i:s");
        if ( $now > $expireToken ){
            $this->addFlash('warning', 'Ce lien est invalide');
            return $this->redirectToRoute('homepage');
        }
        else {
            $user->setIsDisabled(false)
                ->setVerifiedAt(new \DateTime('now', new \DateTimeZone('Europe/Brussels')))
                ->setToken(null)
                ->setTokenValidity(null);

            $manager->persist($user);
            $manager->flush();

            $this->addFlash('success', 'Votre compte a été activé. Vous pouvez maintenant vous connecter');
            return $this->redirectToRoute('login');
        }

    }

    /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('homepage');
        }

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

    /**
     * @param User $user
     * @throws TransportExceptionInterface
     */
    private function sendSignUpConfirmationEmail(User $user)
    {
        $twigContext = [
            'firstName' => $user->getFirstName(),
            'lastName' => $user->getLastName(),
            'token' => $user->getToken(),
        ];

        $this->emailController->sendNoReplyEmail($user->getEmail(), new EmailType(EmailType::REGISTER), $twigContext);
    }
}

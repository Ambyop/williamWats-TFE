<?php

namespace App\Controller\Admin;

use App\Entity\MatchList;
use App\Entity\User;
use App\Form\MatchType;
use App\Repository\MatchListRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminMatchController extends AbstractController
{
    /**
     * @Route("/admin/match", name="admin_match")
     * @param MatchListRepository $matchListRepository
     * @return Response
     */
    public function index(MatchListRepository $matchListRepository): Response
    {
        $matchs = $matchListRepository->findAll();
        return $this->render('admin/admin_match.html.twig', [
            'matchs' => $matchs,
        ]);
    }

    /**
     * @Route("/admin/match/creation", name="admin_match_add")
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function addArticle(EntityManagerInterface $manager, Request $request): Response
    {
        $match = new MatchList();
        $form = $this->createForm(MatchType::class, $match);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $now = new \DateTime('now', new \DateTimeZone('Europe/Brussels'));
            $match->setUpdatedAt($now);
            $manager->persist($match);
            $manager->flush();
            $this->addFlash('success', 'Le Match du ' . date_format($match->getDate(), "d F Y") . ' à ' . $match->getLocation()->getName() . ' a bien été créé.');

            return $this->redirectToRoute('admin_match');
        }
        return $this->render("admin/create_match.html.twig", [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/match/modification/{id}", name="admin_match_edit")
     * @param MatchList $match
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function updateArticle(MatchList $match, EntityManagerInterface $manager, Request $request): Response
    {
        $form = $this->createForm(MatchType::class, $match);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $now = new \DateTime('now', new \DateTimeZone('Europe/Brussels'));
            $match->setUpdatedAt($now);
            $manager->persist($match);
            $manager->flush();
            $this->addFlash('success', 'Le Match du ' . date_format($match->getDate(), "d F Y") . ' à ' . $match->getLocation()->getName() . '  a bien été modifié.');

            return $this->redirectToRoute('admin_match');
        }
        return $this->render("admin/edit_match.html.twig", [
            'match' => $match,
            'form' => $form->createView()
        ]);
    }

    /** @Route("/admin/match/supression/{id}", name="admin_match_remove")
     * @param MatchList $match
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function deleteMatch(MatchList $match, EntityManagerInterface $manager): Response
    {
        $manager->remove($match);
        $manager->flush();
        $this->addFlash('success', 'Le match a bien été supprimé');
        return $this->redirectToRoute('admin_match');
    }

    /**
     * @Route("/admin/match/utilisateur/activation/{id}", name="admin_match_user_activation")
     * @param User $user ;
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function viewUser(User $user, EntityManagerInterface $manager): Response
    {
        $user->setIsDisabled(!$user->getIsDisabled());
        $manager->flush();
        return $this->redirectToRoute('admin_match');
    }
}

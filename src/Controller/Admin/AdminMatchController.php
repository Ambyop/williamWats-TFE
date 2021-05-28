<?php

namespace App\Controller\Admin;

use App\Entity\MatchList;
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
     */
    public function index(MatchListRepository $matchListRepository): Response
    {
        $matchs = $matchListRepository->findAll();
        return $this->render('admin/admin_match.html.twig', [
            'matchs' => $matchs,
        ]);
    }

    /**
     * @Route("/admin/match/create", name="admin_articles_add")
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
            $this->addFlash('success', 'Le Match du' . $match->getDate() . 'à' . $match->getLocation() . ' a bien été créée.');

            return $this->redirectToRoute('admin_match');
        }
        return $this->render("admin/create_match.html.twig", [
            'form' => $form->createView()
        ]);
    }
}

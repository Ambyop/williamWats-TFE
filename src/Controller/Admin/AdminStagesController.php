<?php

namespace App\Controller\Admin;

use App\Entity\Stage;
use App\Form\StageType;
use App\Repository\StageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminStagesController extends AbstractController
{
    /**
     * @Route("/admin/stages", name="admin_stages")
     * @param StageRepository $stageRepository
     * @return Response
     */
    public function index(StageRepository $stageRepository): Response
    {
        $stages = $stageRepository->findAll();
        return $this->render('admin/admin_stages.twig', [
            'stages' => $stages,
        ]);
    }

    /**
     * @Route("/admin/stage/creation", name="admin_stage_create")
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function addArticle(EntityManagerInterface $manager, Request $request): Response
    {
        $stage = new Stage();
        $form = $this->createForm(StageType::class, $stage);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $now = new \DateTime('now', new \DateTimeZone('Europe/Brussels'));
            $stage->setUpdatedAt($now);
            $manager->persist($stage);
            $manager->flush();
            $this->addFlash('success', 'Le stage se déroulant du  ' . date_format($stage->getBeginAt(), "d F Y") . ' au ' . date_format($stage->getEndAt(), "d F Y") . ' a bien été créé.');

            return $this->redirectToRoute('admin_stages');
        }
        return $this->render("admin/create_match.html.twig", [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/stage/modification/{id}", name="admin_stage_edit")
     * @param Stage $stage
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function editArticle(Stage $stage, EntityManagerInterface $manager, Request $request): Response
    {
        $form = $this->createForm(StageType::class, $stage);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $now = new \DateTime('now', new \DateTimeZone('Europe/Brussels'));
            $stage->setUpdatedAt($now);
            $manager->persist($stage);
            $manager->flush();
            $this->addFlash('success', 'Le stage se déroulant du  ' . date_format($stage->getBeginAt(), "d F Y") . ' au ' . date_format($stage->getEndAt(), "d F Y") . ' a bien été modifié.');

            return $this->redirectToRoute('admin_stages');
        }
        return $this->render("admin/create_match.html.twig", [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/equipe/activation/{id}", name="admin_stage_activation")
     * @param Stage $stage
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function viewTeam(Stage $stage, EntityManagerInterface $manager): Response
    {
        $stage->setIsDisabled(!$stage->getIsDisabled());
        $manager->flush();
        return $this->redirectToRoute('admin_stages');
    }

    /** @Route("/admin/stage/supression/{id}", name="admin_stage_remove")
     * @param Stage $stage
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function deleteStage(Stage $stage, EntityManagerInterface $manager): Response
    {
        $manager->remove($stage);
        $manager->flush();
        $this->addFlash('success', 'Le stage a bien été supprimé');
        return $this->redirectToRoute('admin_stages');
    }
}

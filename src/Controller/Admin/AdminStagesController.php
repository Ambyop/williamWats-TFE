<?php

namespace App\Controller\Admin;

use App\Entity\Stage;
use App\Repository\StageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

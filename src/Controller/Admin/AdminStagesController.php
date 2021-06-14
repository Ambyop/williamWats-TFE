<?php

namespace App\Controller\Admin;

use App\Repository\StageRepository;
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
}

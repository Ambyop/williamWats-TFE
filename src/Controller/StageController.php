<?php

namespace App\Controller;

use App\Repository\StageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StageController extends AbstractController
{
    /**
     * @Route("/stages", name="stages")
     */
    public function index(StageRepository $stageRepository): Response
    {
        $stages = $stageRepository->findAll();
        return $this->render('stage/index.html.twig', [
            'stages' => $stages
        ]);
    }
}

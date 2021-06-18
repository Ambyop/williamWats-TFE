<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StageController extends AbstractController
{
    /**
     * @Route("/stages", name="stages")
     */
    public function index(): Response
    {
        return $this->render('stage/index.html.twig', [
            'controller_name' => 'StageController',
        ]);
    }
}

<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminMatchController extends AbstractController
{
    /**
     * @Route("/admin/match", name="admin_match")
     */
    public function index(): Response
    {
        return $this->render('admin_match/index.html.twig', [
            'controller_name' => 'AdminMatchController',
        ]);
    }
}

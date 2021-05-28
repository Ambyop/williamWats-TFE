<?php

namespace App\Controller\Admin;

use App\Repository\MatchListRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
}

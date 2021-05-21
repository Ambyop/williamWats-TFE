<?php

namespace App\Controller\Admin;

use App\Repository\TeamsRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminTeamsController extends AbstractController
{
    /**
     * @Route("/admin/teams", name="admin_teams")
     * @param UserRepository $userRepository
     * @param TeamsRepository $teamsRepository
     */
    public function index(TeamsRepository $teamsRepository, UserRepository $userRepository): Response
    {
        $teams = $teamsRepository->findAll();
        dump($teams[0]->getUsers());
        return $this->render('admin/admin_teams.html.twig', [
            'teams' => $teams,
        ]);
    }
}

<?php

namespace App\Controller\Admin;

use App\Entity\Teams;
use App\Form\TeamType;
use App\Repository\TeamsRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
        return $this->render('admin/admin_teams.html.twig', [
            'teams' => $teams,
        ]);
    }

    /**
     * @Route("/admin/teams/create", name="admin_teams_add")
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function addUser(EntityManagerInterface $manager, Request $request): Response
    {
        $team = new Teams();
        $form = $this->createForm(TeamType::class, $team);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($team);
            $manager->flush();
            $this->addFlash('success', 'L\'Equipe ' . $team->getName() . ' a bien été créé.');

            return $this->redirectToRoute('admin_user');
        }

        return $this->render("admin/create_team.html.twig", [
            'form' => $form->createView()
        ]);
    }
}

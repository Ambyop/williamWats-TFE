<?php

namespace App\Controller\Admin;

use App\Entity\Teams;
use App\Entity\User;
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
     * @param TeamsRepository $teamsRepository
     * @param UserRepository $userRepository
     * @return Response
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

            return $this->redirectToRoute('admin_teams');
        }

        return $this->render("admin/create_team.html.twig", [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/teams/edit/{id}", name="admin_team_edit")
     * @param Teams $team
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function editTeam(Teams $team, EntityManagerInterface $manager, Request $request)
    {
        $form = $this->createForm(TeamType::class, $team);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($team);
            $manager->flush();
            $this->addFlash('success', 'Le cours ' . $team->getName() . ' a bien été édité');

            return $this->redirectToRoute('admin_teams');
        }
        return $this->render('admin/edit_team.html.twig', [
            'form' => $form->createView(),
            'team' => $team
        ]);
    }

    /**
     * @Route("/admin/team/delete/{id}", name="admin_team_delete")
     * @param Teams $team
     * @param EntityManagerInterface $manager
     * @param UserRepository $userRepository
     * @return Response
     */
    public function removeTeam(Teams $team, EntityManagerInterface $manager, UserRepository $userRepository): Response
    {
        $users = $userRepository->findBy([
            'team' => $team->getId()
        ]);
        foreach ($users as $user){
            $team->removeUser($user);
        }
        $manager->remove($team);
        $manager->flush();
        $this->addFlash('success', 'L\'équipe ' . $team->getName() . ' a été supprimé');
        return $this->redirectToRoute('admin_teams');
    }

    /**
     * @Route("/admin/team/{team}/remove/user/{user}", name="admin_team_remove_user")
     * @param Teams $team
     * @param User $user
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function removeUserFromTeam(Teams $team, User $user, EntityManagerInterface $manager ): Response
    {
        $team->removeUser($user);
        $manager->flush();
        return $this->redirectToRoute('admin_teams');
    }


}

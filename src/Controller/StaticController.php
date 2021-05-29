<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StaticController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function home(): Response
    {
        return $this->render('static/homepage.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    /**
     * @Route("/interclubs", name="interclubs")
     */
    public function interclubs(): Response
    {
        return $this->render('static/homepage.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    /**
     * @Route("/about", name="about")
     */
    public function about(): Response
    {
        return $this->render('static/homepage.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}

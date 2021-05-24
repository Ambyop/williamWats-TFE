<?php

namespace App\Controller\Admin;

use App\Repository\ArticlesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminArticlesController extends AbstractController
{
    /**
     * @Route("/admin/articles", name="admin_articles")
     */
    public function index(ArticlesRepository $articlesRepository): Response
    {
        $articles =  $articlesRepository->findAll();
        return $this->render('admin/admin_articles.html.twig', [
            'articles' => $articles,
        ]);
    }
}

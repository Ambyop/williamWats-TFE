<?php

namespace App\Controller\Admin;

use App\Entity\Articles;
use App\Form\ArticleType;
use App\Repository\ArticlesRepository;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminArticlesController extends AbstractController
{
    /**
     * @Route("/admin/articles", name="admin_articles")
     * @param ArticlesRepository $articlesRepository
     * @return Response
     */
    public function index(ArticlesRepository $articlesRepository): Response
    {
        $articles = $articlesRepository->findAll();
        return $this->render('admin/admin_articles.html.twig', [
            'articles' => $articles,
        ]);
    }

    /**
     * @Route("/admin/articles/create", name="admin_articles_add")
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function addArticle(EntityManagerInterface $manager, Request $request): Response
    {
        $article = new Articles();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $slugify = new Slugify();
            $now = new \DateTime('now', new \DateTimeZone('Europe/Brussels'));
            $article->setSlug($slugify->slugify($article->getTitle()))
                ->setCreatedAt($now)
                ->setUpdatedAt($now)
                ->setIsEnabled(true);
            $manager->persist($article);
            $manager->flush();
            $this->addFlash('success', 'L\'actualité ' . $article->getTitle() . ' a bien été créée.');

            return $this->redirectToRoute('admin_articles');
        }
        return $this->render("admin/create_article.html.twig", [
            'form' => $form->createView()
        ]);
    }
}

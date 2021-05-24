<?php

namespace App\Controller\Admin;

use App\Entity\Articles;
use App\Entity\NewActu;
use App\Entity\User;
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

    /**
     * @Route("/admin/articles/edit/{id}", name="admin_article_edit")
     * @param Articles $article
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function updateArticle(Articles $article,EntityManagerInterface $manager, Request $request): Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $slugify = new Slugify();
            $now = new \DateTime('now', new \DateTimeZone('Europe/Brussels'));
            $article->setSlug($slugify->slugify($article->getTitle()))
                ->setUpdatedAt($now);
            $manager->persist($article);
            $manager->flush();
            $this->addFlash('success', 'L\'actualité ' . $article->getTitle() . ' a bien été créée.');

            return $this->redirectToRoute('admin_articles');
        }
        return $this->render("admin/edit_article.html.twig", [
            'article'=> $article,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/articles/activation/{id}", name="admin_article_activation")
     * @param Articles $article
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function viewUser(Articles $article, EntityManagerInterface $manager): Response
    {
        $article->setIsEnabled(!$article->getIsEnabled());
        $manager->flush();
        return $this->redirectToRoute('admin_articles');
    }

    /** @Route("/admin/articles/remove/{id}", name="admin_article_remove")
     * @param Articles $article
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function deleteArticle(Articles $article, EntityManagerInterface $manager): Response
    {
        $manager->remove($article);
        $manager->flush();
        $this->addFlash('success', 'L\'annonce ' . $new->getName() . ' a bien été supprimé');
        return $this->redirectToRoute('admin_articles');
    }
}

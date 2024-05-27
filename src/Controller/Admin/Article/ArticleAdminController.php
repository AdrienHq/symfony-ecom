<?php

namespace App\Controller\Admin\Article;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route("/admin/article", name: 'admin.article.')]
#[IsGranted('ROLE_ADMIN')]
class ArticleAdminController extends AbstractController
{
    public function __construct(
        private readonly ArticleRepository $articleRepository,
    )
    {
    }

    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render("/admin/article/article.html.twig", [
            'articles' => $this->articleRepository->findAll()
        ]);
    }

    #[Route('[/{id}', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Article $article, Request $request, EntityManagerInterface $em): RedirectResponse|Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'The article has been edited');
            return $this->redirectToRoute('admin.article.index');
        }
        return $this->render('admin/article/edit.html.twig', [
            'article' => $article,
            'form' => $form
        ]);
    }

    #[Route('/create', name: 'create')]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($article);
            $em->flush();
            $this->addFlash('success', 'New article created');
            return $this->redirectToRoute('admin.article.index');
        }
        return $this->render('admin/article/create.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(Article $article, EntityManagerInterface $em): RedirectResponse
    {
        $em->remove($article);
        $em->flush();
        $this->addFlash('success', 'Article deleted');
        return $this->redirectToRoute('admin.article.index');
    }
}

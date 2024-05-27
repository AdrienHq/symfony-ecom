<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    #[Route('/articles', name: "article.index")]
    public function index(ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findAll();
        return $this->render('article/index.html.twig', ['articles' => $articles]);
    }

    #[Route('/articles/{slug}-{id}', name: "article.detail", requirements: ['id' => '\d+', 'slug' => '[a-z0-9-]+'], methods: ['GET', 'POST'])]
    public function show(int $id, ArticleRepository $articleRepository): Response
    {
        $article = $articleRepository->find($id);
        return $this->render('article/show.html.twig', ['article' => $article]);
    }
}


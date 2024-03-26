<?php

namespace App\Controller\Category;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CategoryController extends AbstractController
{
    public function __construct(
        private readonly CategoryRepository $categoryRepository,
    )
    {
    }


    #[Route('/category/{id}', name: "category.index", requirements: ['id' => '\d+'])]
    public function index(int $id): Response
    {
        $category = $this->categoryRepository->findOneBy(['id' => $id]);
        $recipes = $category->getRecipes();

        return $this->render("category/list.html.twig", [
            'category' => $category,
            'recipes' => $recipes
        ]);
    }
}

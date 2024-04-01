<?php

namespace App\Controller\Category;

use App\Data\category\SearchDataCategory;
use App\Form\category\SearchFormCategory;
use App\Repository\CategoryRepository;
use App\Repository\RecipesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function index(int $id, Request $request, RecipesRepository $recipesRepository,): Response
    {
        $category = $this->categoryRepository->findOneBy(['id' => $id]);

        $data = new SearchDataCategory();
        $data->page = $request->query->getInt('page', 1);
        $form = $this->createForm(SearchFormCategory::class, $data);
        $form->handleRequest($request);

        $recipes = $recipesRepository->findSearchForCategory($data);

        return $this->render("category/list.html.twig", [
            'category' => $category,
            'recipes' => $recipes,
            'form' => $form
        ]);
    }
}

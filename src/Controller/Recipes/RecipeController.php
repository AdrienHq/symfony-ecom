<?php

namespace App\Controller\Recipes;

use App\Data\recipe\SearchData;
use App\Form\recipe\SearchForm;
use App\Repository\RecipesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RecipeController extends AbstractController
{
    public function __construct(
        private readonly RecipesRepository $recipesRepository
    )
    {
    }

    #[Route('/recipe/{slug}-{id}', name: "recipe.detail", requirements: ['id' => '\d+', 'slug' => '[a-z0-9-]+'])]
    public function index(int $id, string $slug): Response
    {
        $recipe = $this->recipesRepository->find($id);
        $recipes = $this->recipesRepository->findAll();
        if ($recipe->getSlug() != $slug) {
            return $this->redirectToRoute("recipe.detail", ['slug' => $recipe->getSlug(), 'id' => $recipe->getId()]);
        }

        return $this->render("recipes/show.html.twig", [
            'recipe' => $recipe,
            'recipes' => $recipes,
        ]);
    }

    #[Route('/recipes-list', name: "recipes.list")]
    public function listAllRecipes(RecipesRepository $recipesRepository, Request $request): Response
    {

        $data = new SearchData();
        $data->page = $request->query->getInt('page', 1);
        $form = $this->createForm(SearchForm::class, $data);
        $form->handleRequest($request);

        $recipes = $recipesRepository->findSearch($data);

        return $this->render("recipes/list.html.twig", [
            'recipes' => $recipes,
            'form' => $form
        ]);
    }
}
<?php

namespace App\Controller\Api\recipes;

use App\Entity\Recipes;
use App\Repository\RecipesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

class RecipesApiController extends AbstractController
{
    #[Route("/api/recipes/{id}", requirements: ['id' => Requirement::DIGITS])]
    public function getRecipe(Recipes $recipes): JsonResponse
    {
        return $this->json($recipes, 200, [], [
            'groups' => ['recipes.getCollection', 'recipes.get']
        ]);
    }

    #[Route("/api/recipes")]
    public function getCollectionRecipes(RecipesRepository $recipesRepository): JsonResponse
    {
        $recipes = $recipesRepository->findAll();
        return $this->json($recipes, 200, [], [
            'groups' => ['recipes.getCollection']
        ]);
    }

    #[Route("/api/recipesPaginate")]
    public function getCollectionForSpecificPageRecipes(RecipesRepository $recipesRepository, Request $request): JsonResponse
    {
        $recipes = $recipesRepository->findRecipesForSpecificPage($request->query->getInt('page', 1));
        return $this->json($recipes, 200, [], [
            'groups' => ['recipes.getCollection', 'recipes.getCollectionPerPage']
        ]);
    }
}
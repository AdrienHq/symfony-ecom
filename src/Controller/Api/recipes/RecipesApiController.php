<?php

namespace App\Controller\Api\recipes;

use App\Repository\RecipesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class RecipesApiController extends AbstractController
{
    #[Route("/api/recipes")]
    public function getAllRecipes(RecipesRepository $recipesRepository): JsonResponse
    {
        $recipes = $recipesRepository->findAll();
        return $this->json($recipes, 200, [], [
            'groups' => ['recipes.index']
        ]);
    }

}
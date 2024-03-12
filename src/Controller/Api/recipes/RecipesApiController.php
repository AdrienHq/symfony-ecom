<?php

namespace App\Controller\Api\recipes;

use App\DTO\recipes\RecipesPaginationDTO;
use App\Entity\Recipes;
use App\Repository\RecipesRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

class RecipesApiController extends AbstractController
{
    #[Route("/api/recipes/{id}", requirements: ['id' => Requirement::DIGITS])]
    public function getRecipe(Recipes $recipes): JsonResponse
    {
        return $this->json($recipes, 200, [], [
            'groups' => ['recipes.getCollection', 'recipes.get']
        ]);
    }

    #[Route("/api/recipes/old", methods: ["GET"])]
    public function getCollectionRecipes(RecipesRepository $recipesRepository): JsonResponse
    {
        $recipes = $recipesRepository->findAll();
        return $this->json($recipes, 200, [], [
            'groups' => ['recipes.getCollection']
        ]);
    }

    #[Route("/api/recipesPaginate/old", methods: ["GET"])]
    public function getCollectionForSpecificPageRecipes(
        RecipesRepository    $recipesRepository,
        #[MapQueryString]
        ?RecipesPaginationDTO $paginationDTO = null
    ): JsonResponse
    {
        $recipes = $recipesRepository->findRecipesForSpecificPage($paginationDTO->page);
        return $this->json($recipes, 200, [], [
            'groups' => ['recipes.getCollection', 'recipes.getCollectionPerPage']
        ]);
    }

    #[Route("/api/recipes/old", methods: ["POST"])]
    public function postRecipes(
        Request       $request,
        #[MapRequestPayload(
            serializationContext: ['recipes.create']
        )]
        Recipes       $recipes,
        EntityManager $em
    ): JsonResponse
    {
        $recipes->setCreatedAt(new \DateTimeImmutable());
        $recipes->setUpdatedAt(new \DateTimeImmutable());
        $em->persist($recipes);
        $em->flush();
        return $this->json($recipes, 200, [
            'groups' => ['recipes.get', 'recipes.getCollection']
        ]);
    }
}
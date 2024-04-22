<?php

namespace App\Controller\Recipes;

use App\Data\recipe\SearchData;
use App\Entity\Comment;
use App\Entity\Rating;
use App\Form\CommentType;
use App\Form\RatingType;
use App\Form\recipe\SearchForm;
use App\Repository\RatingRepository;
use App\Repository\RecipesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RecipeController extends AbstractController
{
    public function __construct(
        private readonly RecipesRepository      $recipesRepository,
        private readonly EntityManagerInterface $em,
        private readonly RatingRepository       $ratingRepository
    )
    {
    }

    #[Route('/recipe/{slug}-{id}', name: "recipe.detail", requirements: ['id' => '\d+', 'slug' => '[a-z0-9-]+'], methods: ['GET', 'POST'])]
    public function index(int $id, string $slug, Request $request): Response
    {
        $recipe = $this->recipesRepository->find($id);
        $recipes = $this->recipesRepository->findAll();

        $result = $this->ratingRepository->findAverageRatingForRecipe($recipe->getId());

        $ratingCount = $result[0]['ratingCount'];
        $totalStars = $result[0]['totalStars'];
        $averageRating = $ratingCount > 0 ? $totalStars / $ratingCount : 0;

        if ($recipe->getSlug() != $slug) {
            return $this->redirectToRoute("recipe.detail", ['slug' => $recipe->getSlug(), 'id' => $recipe->getId()]);
        }

        $form = $this->createForm(CommentType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $comment = (new Comment())
                ->setAuthor($this->getUser())
                ->setRecipe($recipe)
                ->setContent($form->getData()['content']);
            $this->em->persist($comment);
            $this->em->flush();

            //To empty the form (have a new one)
            $form = $this->createForm(CommentType::class);

            $this->addFlash('success', 'Your comment was posted !');
            $this->redirectToRoute('recipe.detail', ['slug' => $slug, 'id' => $id]);
        }

        $formRating = $this->createForm(RatingType::class);
        $formRating->handleRequest($request);
        if($this->getUser() !== null){
            $recipeRating = $this->ratingRepository->findByUserAndRecipe($this->getUser()->getId(), $recipe->getId());
        } else {
            $recipeRating = null;
        }

        if ($formRating->isSubmitted() && $formRating->isValid()) {
            /** @var Rating $recipeRating */
            if ($recipeRating && $recipeRating->getUser() === $this->getUser() && $recipeRating->getRecipe() === $recipe) {
                $recipeRating->setStars($formRating->getData()['stars']);
                $averageRating = $ratingCount > 0 ? $totalStars / $ratingCount : 0;
                $this->em->flush();
                $this->addFlash('success', 'Your rating was updated !');
                $this->redirectToRoute('recipe.detail', ['slug' => $slug, 'id' => $id]);
            } else {
                $rating = (new Rating())
                    ->setUser($this->getUser())
                    ->setRecipe($recipe)
                    ->setStars($formRating->getData()['stars']);

                $averageRating = $ratingCount > 0 ? $totalStars / $ratingCount : 0;

                $this->em->persist($rating);
                $this->em->flush();

                $this->addFlash('success', 'Your rating was saved !');
                $this->redirectToRoute('recipe.detail', ['slug' => $slug, 'id' => $id]);
            }
        }

        return $this->render("recipes/show.html.twig", [
            'recipe' => $recipe,
            'recipes' => $recipes,
            'userRating' => $recipeRating !== null ? $recipeRating->getStars() : 0,
            'globalRating' => $averageRating,
            'form' => $form->createView(),
            'formRating' => $formRating->createView()
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

    public function renderRecommendedRecipes(): Response
    {
        return $this->render("fragments/_recommendation.html.twig", [
            'recommendedRecipes' => $this->recipesRepository->findPreciseNumberOfResult(4),
        ]);
    }
}
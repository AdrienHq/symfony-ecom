<?php

namespace App\Controller\Recipes;

use App\Data\recipe\SearchData;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Form\recipe\SearchForm;
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
        private readonly EntityManagerInterface $em
    )
    {
    }

    #[Route('/recipe/{slug}-{id}', name: "recipe.detail", requirements: ['id' => '\d+', 'slug' => '[a-z0-9-]+'], methods: ['GET', 'POST'])]
    public function index(int $id, string $slug, Request $request): Response
    {
        $recipe = $this->recipesRepository->find($id);
        $recipes = $this->recipesRepository->findAll();

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

        return $this->render("recipes/show.html.twig", [
            'recipe' => $recipe,
            'recipes' => $recipes,
            'form' => $form->createView()
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
<?php

namespace App\Controller\Admin\Ingredient;

use App\Entity\Ingredient;
use App\Entity\Recipes;
use App\Form\QuantityType;
use App\Form\recipe\RecipesType;
use App\Repository\IngredientRepository;
use App\Repository\RecipesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route("/admin/ingredient", name: 'admin.ingredient.')]
#[IsGranted('ROLE_ADMIN')]
class IngredientAdminController extends AbstractController
{
    public function __construct(
        private readonly IngredientRepository $ingredientRepository
    )
    {
    }


    #[Route('/', name: 'index')]
    public function index(RecipesRepository $recipesRepository, Request $request): Response
    {
        $ingredients = $this->ingredientRepository->findAll();

        return $this->render("admin/ingredients/ingredients.html.twig", [
            'ingredients' => $ingredients,
        ]);
    }

    #[Route('/create', name: 'create')]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $ingredient = new Ingredient();
        $form = $this->createForm(QuantityType::class, $ingredient);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($ingredient);
            $em->flush();
            $this->addFlash('success', 'New recipes created');
            return $this->redirectToRoute('admin.recipes.index');
        }
        return $this->render('admin/recipes/create.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('[/{id}', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Recipes $ingredient, Request $request, EntityManagerInterface $em): RedirectResponse|Response
    {
        $form = $this->createForm(RecipesType::class, $recipe);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'The recipe has been edited');
            return $this->redirectToRoute('admin.recipes.index');
        }
        return $this->render('admin/recipes/edit.html.twig', [
            'recipe' => $recipe,
            'form' => $form
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(Recipes $recipe, EntityManagerInterface $em): RedirectResponse
    {
        $em->remove($recipe);
        $em->flush();
        $this->addFlash('success', 'Recipe deleted');
        return $this->redirectToRoute('admin.recipes.index');
    }

}
<?php

namespace App\Controller\Admin\Ingredient;

use App\Entity\Ingredient;
use App\Form\IngredientType;
use App\Form\recipe\RecipesType;
use App\Repository\IngredientRepository;
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
    public function index(Request $request): Response
    {
        $ingredients = $this->ingredientRepository->findIngredientsForSpecificPage($request->query->getInt('page', 1));

        return $this->render("admin/ingredients/ingredients.html.twig", [
            'ingredients' => $ingredients,
        ]);
    }

    #[Route('/create', name: 'create')]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $ingredient = new Ingredient();
        $form = $this->createForm(IngredientType::class, $ingredient);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($ingredient);
            $em->flush();
            $this->addFlash('success', 'New ingredient created');
            return $this->redirectToRoute('admin.ingredient.index');
        }
        return $this->render('admin/ingredients/create.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('[/{id}', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Ingredient $ingredient, Request $request, EntityManagerInterface $em): RedirectResponse|Response
    {
        $form = $this->createForm(IngredientType::class, $ingredient);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'The ingredient has been edited');
            return $this->redirectToRoute('admin.ingredient.index');
        }
        return $this->render('admin/ingredients/edit.html.twig', [
            'ingredient' => $ingredient,
            'form' => $form
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(Ingredient $ingredient, EntityManagerInterface $em): RedirectResponse
    {
        $em->remove($ingredient);
        $em->flush();
        $this->addFlash('success', 'Ingredient deleted');
        return $this->redirectToRoute('admin.ingredient.index');
    }

}
<?php

namespace App\Controller\Admin\Recipes;

use App\Entity\Recipes;
use App\Form\RecipesType;
use App\Repository\RecipesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route("/admin/recipes", name: 'admin.recipes.')]
class RecipesAdminController extends AbstractController
{
    public function __construct(
        private readonly RecipesRepository $recipesRepository
    )
    {
    }

    #[Route('/', name: 'index')]
    public function index()
    {
        return $this->render("/admin/recipes/recipes.html.twig",[
            'recipes' => $this->recipesRepository->findAll()
        ]);
    }

    #[Route('[/{id}/edit', name: 'edit')]
    public function edit(Recipes $recipe,Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(RecipesType::class, $recipe);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->flush();
            $this->addFlash('success', 'The recipe has been edited');
            return $this->redirectToRoute('admin.recipes.index');
        }
        return $this->render('admin/recipes/edit.html.twig', [
            'recipe' => $recipe,
            'form' => $form
        ]);
    }
}
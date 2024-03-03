<?php

namespace App\Controller\Admin\Recipes;

use App\Repository\RecipesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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


}
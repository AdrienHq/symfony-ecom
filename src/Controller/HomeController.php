<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\CourseRepository;
use App\Repository\RecipesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    public function __construct(
        private readonly CategoryRepository $categoryRepository,
        private readonly CourseRepository   $courseRepository,
        private readonly RecipesRepository  $recipesRepository
    )
    {
    }

    #[Route('/', name: "index")]
    public function index(): Response
    {
        $randomResult = $this->recipesRepository->findPreciseNumberOfResult(3);
        $popularRecipes = $this->recipesRepository->findRecipesOrderedByPopularity(5);

        return $this->render("base.html.twig", [
            "randomRecipes" => $randomResult,
            "popularRecipes" => $popularRecipes
        ]);
    }

    public function renderCategoriesFragmentNavbar(): Response
    {
        return $this->render("fragments/_navbarCategories.html.twig", [
            'categories' => $this->categoryRepository->findAll(),
        ]);
    }

    public function renderCoursesFragmentNavbar(): Response
    {
        return $this->render("fragments/_navbarCourses.html.twig", [
            'courses' => $this->courseRepository->findAll(),
        ]);
    }
}
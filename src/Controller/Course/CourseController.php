<?php

namespace App\Controller\Course;

use App\Data\course\SearchDataCourse;
use App\Form\course\SearchFormCourse;
use App\Repository\CategoryRepository;
use App\Repository\CourseRepository;
use App\Repository\RecipesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CourseController extends AbstractController
{
    public function __construct(
        private readonly CourseRepository $courseRepository,
    )
    {
    }


    #[Route('/course/{id}', name: "course.index", requirements: ['id' => '\d+'])]
    public function listAllRecipes(int $id, RecipesRepository $recipesRepository, Request $request): Response
    {
        $course = $this->courseRepository->findOneBy(['id' => $id]);

        $data = new SearchDataCourse();
        $data->page = $request->query->getInt('page', 1);
        $form = $this->createForm(SearchFormCourse::class, $data);
        $form->handleRequest($request);

        $recipes = $recipesRepository->findSearchForCourse($data);

        return $this->render("recipes/list.html.twig", [
            'course' => $course,
            'recipes' => $recipes,
            'form' => $form
        ]);
    }
}

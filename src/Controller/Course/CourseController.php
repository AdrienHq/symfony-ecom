<?php

namespace App\Controller\Course;

use App\Repository\CategoryRepository;
use App\Repository\CourseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function index(int $id): Response
    {
        $course = $this->courseRepository->findOneBy(['id' => $id]);
        $recipes = $course->getRecipes();

        return $this->render("course/list.html.twig", [
            'course' => $course,
            'recipes' => $recipes
        ]);
    }
}

<?php

namespace App\Controller\Admin\Recipes;

use App\Entity\Recipes;
use App\Form\recipe\RecipesType;
use App\Repository\RecipesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route("/admin/recipes", name: 'admin.recipes.')]
#[IsGranted('ROLE_ADMIN')]
class RecipesAdminController extends AbstractController
{
    public function __construct(
        private readonly RecipesRepository $recipesRepository
    )
    {
    }

    #[Route('/', name: 'index')]
    public function index(RecipesRepository $recipesRepository, Request $request): Response
    {
        $recipes = $recipesRepository->findRecipesForSpecificPage($request->query->getInt('page', 1));

        return $this->render("admin/recipes/recipes.html.twig", [
            'recipes' => $recipes,
        ]);
    }

    #[Route('[/{id}', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Recipes $recipe, Request $request, EntityManagerInterface $em): RedirectResponse|Response
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

    #[Route('/create', name: 'create')]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $recipe = new Recipes();
        $form = $this->createForm(RecipesType::class, $recipe);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($recipe);
            $em->flush();
            $this->addFlash('success', 'New recipes created');
            return $this->redirectToRoute('admin.recipes.index');
        }
        return $this->render('admin/recipes/create.html.twig', [
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


    #[Route('/upload-image', name: 'upload_image', methods: ['POST'])]
    public function uploadImage(Request $request, SluggerInterface $slugger): JsonResponse
    {
        $file = $request->files->get('file');
        if ($file) {
            $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

            try {
                $file->move(
                    $this->getParameter('uploads_directory'),
                    $newFilename
                );
                return new JsonResponse(['imageUrl' => '/uploads/'.$newFilename]);
            } catch (FileException $e) {
                return new JsonResponse(['error' => 'Failed to upload image'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        return new JsonResponse(['error' => 'No file uploaded'], Response::HTTP_BAD_REQUEST);
    }
}
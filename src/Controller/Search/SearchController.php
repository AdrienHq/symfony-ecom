<?php

declare(strict_types=1);

namespace App\Controller\Search;

use App\Repository\RecipesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    #[Route('/search', name: 'search', methods: ['GET'])]
    public function search(Request $request, RecipesRepository $repository): JsonResponse
    {
        $query = $request->query->get('q');

        // Perform the search using your repository
        $results = $repository->findBySearchQuery($query);

        // Convert results to an array
        $data = [];
        foreach ($results as $result) {
            $data[] = [
                'id' => $result->getId(),
                'name' => $result->getName(),
                // add other fields as needed
            ];
        }

        return new JsonResponse($data);
    }
}

<?php

namespace App\Api\Provider\Recipes;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Repository\RecipesRepository;

class RecipesGetProvider implements ProviderInterface
{
    public function __construct(
        private readonly RecipesRepository $recipesRepository
    )
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): array|null|object
    {
        return $this->recipesRepository->find($uriVariables['id']);
    }


}
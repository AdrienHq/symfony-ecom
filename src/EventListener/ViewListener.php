<?php

namespace App\EventListener;

use App\Repository\RecipesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class ViewListener
{
    public function __construct(
        private readonly RecipesRepository $recipesRepository,
        private readonly EntityManagerInterface $em
    )
    {
    }

    #[AsEventListener(event: KernelEvents::REQUEST)]
    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();

        // Check if the current route matches 'recipe.detail'
        if ($request->attributes->get('_route') === 'recipe.detail') {

            // Get the recipe ID from the request attributes
            $id = $request->attributes->get('id');

            // Fetch the recipe entity
            $recipe = $this->recipesRepository->find($id);

            // Increment the 'numberOfView' property
            $numberOfView = $recipe->getNumberViews();
            $recipe->setNumberViews($numberOfView + 1);

            // Persist the changes to the database
            $this->em->flush();
        }
    }
}

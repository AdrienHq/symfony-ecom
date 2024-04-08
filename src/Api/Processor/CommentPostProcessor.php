<?php

namespace App\Api\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Comment;
use App\Repository\RecipesRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class CommentPostProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly Security               $security,
        private readonly EntityManagerInterface $em,
        private readonly RecipesRepository $recipesRepository
    )
    {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        $comment = (new Comment())
            ->setAuthor($this->security->getUser())
            ->setContent($uriVariables['content'])
            ->setRecipe($this->recipesRepository->find($uriVariables['id']));

        $this->em->persist($comment);
        $this->em->flush();

        return $comment;
    }
}
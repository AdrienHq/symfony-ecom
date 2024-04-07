<?php

namespace App\Api\Provider\Comment;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Repository\CommentRepository;

class CommentGetProvider implements ProviderInterface
{
    public function __construct(
        private readonly CommentRepository $commentRepository
    )
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): array|null|object
    {
        return $this->commentRepository->find($uriVariables['id']);
    }
}
<?php

namespace App\Controller;

use App\Entity\Comment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CommentController extends AbstractController
{
    public function __construct(
        private readonly Security $security
    )
    {
    }

    #[Route('/comment/{id}', name: "comment.delete")]
    public function listAllRecipes(Comment $comment, EntityManagerInterface $em, Request $request): Response
    {
        $slug = $comment->getRecipe()->getSlug();
        $id = $comment->getRecipe()->getId();

        if ($this->security->isGranted('ROLE_USER') && $this->getUser() === $comment->getAuthor()) {
            if ($this->isCsrfTokenValid('delete' . $comment->getId(), $request->request->get('_token'))) {
                $em->remove($comment);
                $em->flush();

                $this->addFlash('danger', 'Your comment was deleted !');
            }
        }

        return $this->redirectToRoute('recipe.detail', ['slug' => $slug, 'id' => $id]);
    }
}
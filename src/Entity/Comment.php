<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Common\Filter\SearchFilterInterface;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Provider\Comment\CommentGetProvider;
use App\Repository\CommentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ApiResource(
    operations: [
        new Get(
            uriTemplate: '/comment/{id}',
            normalizationContext: ['groups' => [Comment::ITEM], 'skip_null_values' => false],
            name: 'api_comment_get',
            provider: CommentGetProvider::class,
        ),
        new GetCollection(
            uriTemplate: '/comments',
            paginationEnabled: false,
            normalizationContext: ['groups' => [Comment::ITEM], 'skip_null_values' => false],
            name: 'api_comments_get_collection',
        ),
    ]
)]
#[ApiFilter(SearchFilter::class, properties: ['recipe' => SearchFilterInterface::STRATEGY_EXACT])]
#[ORM\Entity(repositoryClass: CommentRepository::class)]
class Comment
{
    const ITEM = 'COMMENTS_ITEM';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([Comment::ITEM])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'comments')]
    #[Groups([Comment::ITEM])]
    private ?Recipes $recipe = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups([Comment::ITEM])]
    private ?string $content = null;

    #[ORM\Column]
    #[Groups([Comment::ITEM])]
    private ?\DateTimeImmutable $publishedAt = null;

    #[ORM\ManyToOne(inversedBy: 'comments')]
    #[Groups([Comment::ITEM])]
    private ?User $author = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRecipe(): ?Recipes
    {
        return $this->recipe;
    }

    public function setRecipe(?Recipes $recipe): static
    {
        $this->recipe = $recipe;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getPublishedAt(): ?\DateTimeImmutable
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(\DateTimeImmutable $publishedAt): static
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): static
    {
        $this->author = $author;

        return $this;
    }
}

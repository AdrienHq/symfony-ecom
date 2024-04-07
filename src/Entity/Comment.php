<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Common\Filter\SearchFilterInterface;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Api\Processor\CommentPostProcessor;
use App\Api\Provider\Comment\CommentGetProvider;
use App\Controller\Api\CreateComment;
use App\Repository\CommentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

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
            name: 'api_comment_get_collection',
        ),
        new Post(
            uriTemplate: '/comments/{id}',
            normalizationContext: ['groups' => [Comment::ITEM]],
            denormalizationContext: ['groups' => [Comment::POST]],
            name: 'api_comment_post',
            processor: CommentPostProcessor::class
        )
    ]
)]
#[ApiFilter(SearchFilter::class, properties: ['recipe' => SearchFilterInterface::STRATEGY_EXACT])]
#[ORM\Entity(repositoryClass: CommentRepository::class)]
class Comment
{
    const ITEM = 'COMMENT_ITEM';
    const POST = 'COMMENT_POST';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([Comment::ITEM])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'comments')]
    #[Groups([Comment::ITEM])]
    private ?Recipes $recipe = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\NotBlank(message: 'comment.blank')]
    #[Assert\Length(min: 5, max: 10000, minMessage: 'The comment is too short', maxMessage: 'The comment is too long')]
    #[Groups([Comment::ITEM, Comment::POST])]
    private ?string $content = null;

    #[ORM\Column]
    #[Groups([Comment::ITEM])]
    private ?\DateTimeImmutable $publishedAt = null;

    #[ORM\ManyToOne(inversedBy: 'comments')]
    #[Groups([Comment::ITEM])]
    private ?User $author = null;

    public function __construct()
    {
        $this->publishedAt = new \DateTimeImmutable();
    }

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

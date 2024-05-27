<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Api\Provider\Recipes\RecipesGetProvider;
use App\Repository\RecipesRepository;
use App\Validator\Entry;
use App\Validator\Sanitizer;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: RecipesRepository::class)]
#[UniqueEntity('name')]
#[UniqueEntity('slug')]
#[Vich\Uploadable()]
#[ApiResource(
    operations: [
        new Get(
            uriTemplate: '/recipes/{id}',
            normalizationContext: ['groups' => [Recipes::ITEM], 'skip_null_values' => false],
            name: 'api_recipes_get',
            provider: RecipesGetProvider::class,
        ),
        new GetCollection(
            uriTemplate: '/recipes',
            paginationEnabled: false,
            normalizationContext: ['groups' => [Recipes::ITEM], 'skip_null_values' => false],
            name: 'api_recipes_get_collection',
        ),
    ]
)]
class Recipes
{
    const ITEM = 'RECIPES_ITEM';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([Recipes::ITEM, Comment::ITEM])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(min: 4)]
    #[Assert\NotBlank()]
    #[Sanitizer()]
    #[Groups([Recipes::ITEM])]
    private string $name;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Sanitizer()]
    private string $description = '';

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Sanitizer()]
    private string $questions = '';


    #[ORM\Column]
    #[Groups([Recipes::ITEM])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups([Recipes::ITEM])]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\Column(nullable: true)]
    #[Assert\Positive()]
    private ?int $duration = 0;

    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'recipes')]
    private Collection $category;

    #[ORM\ManyToOne(inversedBy: 'recipes')]
    private ?Course $course = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $thumbnail = null;

    #TODO Manage the size of the file
    #[Vich\UploadableField(mapping: 'recipes', fileNameProperty: 'thumbnail')]
    #[Assert\Image]
    private ?File $thumbnailFile = null;

    #[ORM\Column]
    private ?bool $vegetarian = null;

    #[ORM\Column]
    private ?int $numberViews = 0;

    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'recipe')]
    #[ORM\OrderBy(['publishedAt' => 'DESC'])]
    private Collection $comments;

    #[ORM\OneToMany(targetEntity: Rating::class, mappedBy: 'recipe')]
    private Collection $ratings;

    #[ORM\OneToMany(targetEntity: Quantity::class, mappedBy: 'recipe', cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[Assert\Valid]
    private Collection $quantities;

    #[ORM\OneToMany(targetEntity: Favorites::class, mappedBy: 'favoriteRecipe', orphanRemoval: true)]
    private Collection $favorites;

    public function __construct()
    {
        $this->category = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->ratings = new ArrayCollection();
        $this->quantities = new ArrayCollection();
        $this->favorites = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getQuestions(): string
    {
        return $this->questions;
    }

    public function setQuestions(string $questions): static
    {
        $this->questions = $questions;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(?int $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategory(): Collection
    {
        return $this->category;
    }

    public function addCategory(Category $category): static
    {
        if (!$this->category->contains($category)) {
            $this->category->add($category);
        }

        return $this;
    }

    public function removeCategory(Category $category): static
    {
        $this->category->removeElement($category);

        return $this;
    }

    public function getCourse(): ?Course
    {
        return $this->course;
    }

    public function setCourse(?Course $course): static
    {
        $this->course = $course;

        return $this;
    }

    public function addCourse(Course $course): static
    {
        if (!$this->course->contains($course)) {
            $this->course->add($course);
        }

        return $this;
    }

    public function removeCourse(Course $course): static
    {
        $this->course->removeElement($course);

        return $this;
    }

    public function getThumbnail(): ?string
    {
        return $this->thumbnail;
    }

    public function setThumbnail(?string $thumbnail): static
    {
        $this->thumbnail = $thumbnail;

        return $this;
    }

    public function getThumbnailFile(): ?File
    {
        return $this->thumbnailFile;
    }

    public function setThumbnailFile(?File $thumbnailFile): static
    {
        $this->thumbnailFile = $thumbnailFile;
        return $this;
    }

    public function isVegetarian(): ?bool
    {
        return $this->vegetarian;
    }

    public function setVegetarian(?bool $vegetarian): self
    {
        $this->vegetarian = $vegetarian;

        return $this;
    }

    public function getNumberViews(): ?int
    {
        return $this->numberViews;
    }

    public function setNumberViews(int $numberViews): static
    {
        $this->numberViews = $numberViews;

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setRecipe($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getRecipe() === $this) {
                $comment->setRecipe(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Rating>
     */
    public function getRatings(): Collection
    {
        return $this->ratings;
    }

    public function addRating(Rating $rating): static
    {
        if (!$this->ratings->contains($rating)) {
            $this->ratings->add($rating);
            $rating->setRecipe($this);
        }

        return $this;
    }

    public function removeRating(Rating $rating): static
    {
        if ($this->ratings->removeElement($rating)) {
            // set the owning side to null (unless already changed)
            if ($rating->getRecipe() === $this) {
                $rating->setRecipe(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Quantity>
     */
    public function getQuantities(): Collection
    {
        return $this->quantities;
    }

    public function addQuantity(Quantity $quantity): static
    {
        if (!$this->quantities->contains($quantity)) {
            $this->quantities->add($quantity);
            $quantity->setRecipe($this);
        }

        return $this;
    }

    public function removeQuantity(Quantity $quantity): static
    {
        if ($this->quantities->removeElement($quantity)) {
            // set the owning side to null (unless already changed)
            if ($quantity->getRecipe() === $this) {
                $quantity->setRecipe(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Favorites>
     */
    public function getFavorites(): Collection
    {
        return $this->favorites;
    }

    public function addFavorite(Favorites $favorite): static
    {
        if (!$this->favorites->contains($favorite)) {
            $this->favorites->add($favorite);
            $favorite->setFavoriteRecipe($this);
        }

        return $this;
    }

    public function removeFavorite(Favorites $favorite): static
    {
        if ($this->favorites->removeElement($favorite)) {
            // set the owning side to null (unless already changed)
            if ($favorite->getFavoriteRecipe() === $this) {
                $favorite->setFavoriteRecipe(null);
            }
        }

        return $this;
    }
}

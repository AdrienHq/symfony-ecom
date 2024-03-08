<?php

namespace App\Entity;

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
class Recipes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['recipes.index'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(min: 4)]
    #[Assert\NotBlank()]
    #[Sanitizer()]
    #[Groups(['recipes.index'])]
    private string $name;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Sanitizer()]
    #[Groups(['recipes.index'])]
    private string $content = '';

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Sanitizer()]
    #[Groups(['recipes.index'])]
    private string $description = '';

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Sanitizer()]
    #[Groups(['recipes.index'])]
    private string $questions = '';

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Sanitizer()]
    #[Groups(['recipes.index'])]
    private string $recipeSteps = '';

    #[ORM\Column]
    #[Groups(['recipes.index'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['recipes.index'])]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(length: 255)]
    #[Groups(['recipes.index'])]
    private ?string $slug = null;

    #[ORM\Column(nullable: true)]
    #[Assert\Positive()]
    #[Groups(['recipes.index'])]
    private ?int $duration = 0;

    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'recipes')]
    #[Groups(['recipes.index'])]
    private Collection $category;

    #[ORM\ManyToOne(inversedBy: 'recipes')]
    #[Groups(['recipes.index'])]
    private ?Course $course = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['recipes.index'])]
    private ?string $thumbnail = null;

    #TODO Manage the size of the file
    #[Vich\UploadableField(mapping: 'recipes', fileNameProperty: 'thumbnail')]
    #[Groups(['recipes.index'])]
    private ?File $thumbnailFile = null;

    public function __construct()
    {
        $this->category = new ArrayCollection();
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

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

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

    public function getRecipeSteps(): string
    {
        return $this->recipeSteps;
    }

    public function setRecipeSteps(string $recipeSteps): static
    {
        $this->recipeSteps = $recipeSteps;

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
}

<?php

namespace App\Entity;

use App\Repository\RecipesRepository;
use App\Validator\Entry;
use App\Validator\Sanitizer;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RecipesRepository::class)]
#[UniqueEntity('name')]
#[UniqueEntity('slug')]
class Recipes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(min: 4)]
    #[Assert\NotBlank()]
    #[Sanitizer()]
    private string $name;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Sanitizer()]
    private string $content = '';

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Sanitizer()]
    private string $description = '';

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Sanitizer()]
    private string $questions = '';

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Sanitizer()]
    private string $recipeSteps = '';

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\Column(nullable: true)]
    #[Assert\Positive()]
    #[Assert\GreaterThan(300)]
    private ?int $duration = 0;

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
}

<?php

namespace App\Entity;

use App\Repository\CourseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: CourseRepository::class)]
#[UniqueEntity('name')]
class Course
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(targetEntity: Recipes::class, mappedBy: 'course')]
    private Collection $recipes;

    public function __construct()
    {
        $this->recipes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Recipes>
     */
    public function getRecipes(): Collection
    {
        return $this->recipes;
    }

    public function addRecipe(Recipes $recipe): static
    {
        if (!$this->recipes->contains($recipe)) {
            $this->recipes->add($recipe);
            $recipe->addCourse($this);
        }

        return $this;
    }

    public function removeRecipe(Recipes $recipe): static
    {
//        if ($this->recipes->removeElement($recipe)) {
//            // set the owning side to null (unless already changed)
//            if ($recipe->getCourse() === $this) {
//                $recipe->removeCourse(null);
//            }
//        }
//
//        return $this;
        if ($this->recipes->removeElement($recipe)) {
            $recipe->removeCourse($this);
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->name;
    }
}

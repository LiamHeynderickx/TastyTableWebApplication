<?php

namespace App\Entity;

use App\Repository\RecipesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: RecipesRepository::class)]
#[Broadcast]
class Recipes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $userId = null;

    #[ORM\Column(length: 100)]
    private ?string $recipeName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $recipeDescription = null;

    #[ORM\Column(type: Types::BLOB, nullable: true)]
    private $picture;

    #[ORM\Column]
    private ?int $cost = null;

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $ingredients = [];

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $ingredients_amounts = [];

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $ingredients_units = [];


    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $instructions = [];

    #[ORM\Column]
    private ?int $time = null;

    #[ORM\Column(nullable: true)]
    private ?float $calories = null;

    #[ORM\Column(nullable: true)]
    private ?float $fat = null;

    #[ORM\Column(nullable: true)]
    private ?float $carbs = null;

    #[ORM\Column(nullable: true)]
    private ?float $protein = null;

    #[ORM\Column]
    private ?int $servings = null;

    #[ORM\Column(length: 100)]
    private ?string $diet = null;

    #[ORM\Column(length: 100)]
    private ?string $type = null;

    /**
     * @var Collection<int, Posts>
     */
    #[ORM\OneToMany(targetEntity: Posts::class, mappedBy: 'recipeId')]
    private Collection $RecipePosts;

    public function __construct()
    {
        $this->RecipePosts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): static
    {
        $this->userId = $userId;

        return $this;
    }

    public function getRecipeName(): ?string
    {
        return $this->recipeName;
    }

    public function setRecipeName(string $recipeName): static
    {
        $this->recipeName = $recipeName;

        return $this;
    }

    public function getRecipeDescription(): ?string
    {
        return $this->recipeDescription;
    }

    public function setRecipeDescription(?string $recipeDescription): static
    {
        $this->recipeDescription = $recipeDescription;

        return $this;
    }

    public function getPicture()
    {
        return $this->picture;
    }

    public function setPicture($picture): static
    {
        $this->picture = $picture;

        return $this;
    }

    public function getCost(): ?int
    {
        return $this->cost;
    }

    public function setCost(int $cost): static
    {
        $this->cost = $cost;

        return $this;
    }

    public function getIngredients(): ?array
    {
        return $this->ingredients;
    }

    public function setIngredients(?array $ingredients): static
    {
        $this->ingredients = $ingredients;

        return $this;
    }

    public function getIngredientsAmounts(): ?array
    {
        return $this->ingredients_amounts;
    }

    public function setIngredientsAmounts(?array $ingredients_amounts): static
    {
        $this->ingredients_amounts = $ingredients_amounts;

        return $this;
    }

    public function getIngredientsUnits(): ?array
    {
        return $this->ingredients_units;
    }

    public function setIngredientsUnits(?array $ingredients_units): static
    {
        $this->ingredients_units = $ingredients_units;

        return $this;
    }

    public function getInstructions(): ?array
    {
        return $this->instructions;
    }

    public function setInstructions(?array $instructions): static
    {
        $this->instructions = $instructions;

        return $this;
    }

    public function getTime(): ?int
    {
        return $this->time;
    }

    public function setTime(int $time): static
    {
        $this->time = $time;

        return $this;
    }

    public function getCalories(): ?float
    {
        return $this->calories;
    }

    public function setCalories(?float $calories): static
    {
        $this->calories = $calories;

        return $this;
    }

    public function getFat(): ?float
    {
        return $this->fat;
    }

    public function setFat(?float $fat): static
    {
        $this->fat = $fat;

        return $this;
    }

    public function getCarbs(): ?float
    {
        return $this->carbs;
    }

    public function setCarbs(?float $carbs): static
    {
        $this->carbs = $carbs;

        return $this;
    }

    public function getProtein(): ?float
    {
        return $this->protein;
    }

    public function setProtein(?float $protein): static
    {
        $this->protein = $protein;

        return $this;
    }

    public function getServings(): ?int
    {
        return $this->servings;
    }

    public function setServings(int $servings): static
    {
        $this->servings = $servings;

        return $this;
    }

    public function getDiet(): ?string
    {
        return $this->diet;
    }

    public function setDiet(string $diet): static
    {
        $this->diet = $diet;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection<int, Posts>
     */
    public function getRecipePosts(): Collection
    {
        return $this->RecipePosts;
    }

    public function addRecipePost(Posts $recipePost): static
    {
        if (!$this->RecipePosts->contains($recipePost)) {
            $this->RecipePosts->add($recipePost);
            $recipePost->setRecipeId($this);
        }

        return $this;
    }

    public function removeRecipePost(Posts $recipePost): static
    {
        if ($this->RecipePosts->removeElement($recipePost)) {
            // set the owning side to null (unless already changed)
            if ($recipePost->getRecipeId() === $this) {
                $recipePost->setRecipeId(null);
            }
        }

        return $this;
    }
}

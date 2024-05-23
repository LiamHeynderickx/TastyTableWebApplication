<?php

namespace App\Entity;

use App\Repository\SavedRecipesRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: SavedRecipesRepository::class)]
#[Broadcast]
class SavedRecipes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'userComments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $userId = null;

    #[ORM\Column]
    private ?int $recipeId = null;

    #[ORM\Column]
    private ?bool $isApi = null;

    #[ORM\Column]
    private ?bool $isMyRecipe = null;

    public function getIsMyRecipe(): ?bool
    {
        return $this->isMyRecipe;
    }

    public function setIsMyRecipe(?bool $isMyRecipe): void
    {
        $this->isMyRecipe = $isMyRecipe;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?User
    {
        return $this->userId;
    }

    public function setUserId(User $userId): static
    {
        $this->userId = $userId;

        return $this;
    }

    public function getRecipeId(): ?string
    {
        return $this->recipeId;
    }

    public function setRecipeId(string $recipeId): static
    {
        $this->recipeId = $recipeId;

        return $this;
    }

    public function getIsApi(): ?bool
    {
        return $this->isApi;
    }

    public function setIsApi(bool $isApi): static
    {
        $this->isApi = $isApi;

        return $this;
    }
}

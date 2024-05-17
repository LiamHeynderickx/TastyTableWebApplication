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

    #[ORM\Column(length: 100)]
    private ?string $userId = null;

    #[ORM\Column(length: 100)]
    private ?string $recipeId = null;

    #[ORM\Column]
    private ?bool $isApi = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?string
    {
        return $this->userId;
    }

    public function setUserId(string $userId): static
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

    public function isApi(): ?bool
    {
        return $this->isApi;
    }

    public function setApi(bool $isApi): static
    {
        $this->isApi = $isApi;

        return $this;
    }
}

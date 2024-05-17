<?php

namespace App\Entity;

use App\Repository\FollowingRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: FollowingRepository::class)]
#[Broadcast]
class Following
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $userId = null;

    #[ORM\Column(length: 100)]
    private ?string $followingId = null;

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

    public function getFollowingId(): ?string
    {
        return $this->followingId;
    }

    public function setFollowingId(string $followingId): static
    {
        $this->followingId = $followingId;

        return $this;
    }
}

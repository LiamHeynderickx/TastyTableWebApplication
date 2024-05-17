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

    #[ORM\Column]
    private ?int $userId = null;

    #[ORM\Column]
    private ?int $followingId = null;

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

    public function getFollowingId(): ?int
    {
        return $this->followingId;
    }

    public function setFollowingId(int $followingId): static
    {
        $this->followingId = $followingId;

        return $this;
    }
}

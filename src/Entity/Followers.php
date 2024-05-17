<?php

namespace App\Entity;

use App\Repository\FollowersRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: FollowersRepository::class)]
#[Broadcast]
class Followers
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $userId = null;

    #[ORM\Column(length: 100)]
    private ?string $followerId = null;

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

    public function getFollowerId(): ?string
    {
        return $this->followerId;
    }

    public function setFollowerId(string $followerId): static
    {
        $this->followerId = $followerId;

        return $this;
    }
}

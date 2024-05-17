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

    #[ORM\Column]
    private ?int $userId = null;

    #[ORM\Column]
    private ?int $followerId = null;

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

    public function getFollowerId(): ?int
    {
        return $this->followerId;
    }

    public function setFollowerId(int $followerId): static
    {
        $this->followerId = $followerId;

        return $this;
    }
}

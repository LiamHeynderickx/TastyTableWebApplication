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

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'user_id', nullable: false)]
    private ?User $userId = null;

    #[ORM\ManyToOne(inversedBy: 'userFollowing')]
    #[ORM\JoinColumn(name: 'following_id',nullable: false)]
    private ?User $followingId = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?User
    {
        return $this->userId;
    }

    public function setUserId(?User $userId): static
    {
        $this->userId = $userId;

        return $this;
    }

    public function getFollowingId(): ?User
    {
        return $this->followingId;
    }

    public function setFollowingId(?User $followingId): static
    {
        $this->followingId = $followingId;

        return $this;
    }
}
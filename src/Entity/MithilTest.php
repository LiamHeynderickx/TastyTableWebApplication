<?php

namespace App\Entity;

use App\Repository\MithilTestRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: MithilTestRepository::class)]
#[Broadcast]
class MithilTest
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $col1 = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCol1(): ?string
    {
        return $this->col1;
    }

    public function setCol1(?string $col1): static
    {
        $this->col1 = $col1;

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table('user')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\Column, GeneratedValue]
    private int $id;

    #[ORM\Column]
    private string $email;

    #[ORM\Column]
    private string $password;

    #[ORM\Column]
    private string $username;

    #[ORM\Column]
    private string $name;

    #[ORM\Column]
    private string $surname;

    #[ORM\Column(name:"dietPreference")]
    private string $dietPreference;

    /**
     * @var Collection<int, Following>
     */
    #[ORM\OneToMany(targetEntity: Following::class, mappedBy: 'followingId', orphanRemoval: true)]
    private Collection $userFollowing;

    /**
     * @var Collection<int, Followers>
     */
    #[ORM\OneToMany(targetEntity: Followers::class, mappedBy: 'followerId', orphanRemoval: true)]
    private Collection $userFollowers;

    /**
     * @var Collection<int, Posts>
     */
    #[ORM\OneToMany(targetEntity: Posts::class, mappedBy: 'creatorId', orphanRemoval: true)]
    private Collection $userPosts;

    /**
     * @var Collection<int, Comments>
     */
    #[ORM\OneToMany(targetEntity: Comments::class, mappedBy: 'userId', orphanRemoval: true)]
    private Collection $userComments;

    public function __construct()
    {
        $this->userFollowing = new ArrayCollection();
        $this->userFollowers = new ArrayCollection();
        $this->userPosts = new ArrayCollection();
        $this->userComments = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): User
    {
        $this->email = $email;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): User
    {
        $this->password = $password;
        return $this;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): User
    {
        $this->username = $username;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): User
    {
        $this->name = $name;
        return $this;
    }

    public function getSurname(): string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): User
    {
        $this->surname = $surname;
        return $this;
    }

    public function getDietPreference(): string
    {
        return $this->dietPreference;
    }

    public function setDietPreference(string $dietPreference): User
    {
        $this->dietPreference = $dietPreference;
        return $this;
    }
    // Implement UserInterface methods
    public function getRoles(): array
    {
        // For simplicity, returning a static role
        return ['ROLE_USER'];
    }

    public function getSalt(): ?string
    {
        // Not needed when using bcrypt or sodium
        return null;
    }

    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
    }
    public function getUserIdentifier(): string
    {
        return $this->username;
    }

    /**
     * @return Collection<int, Following>
     */
    public function getUserFollowing(): Collection
    {
        return $this->userFollowing;
    }

    public function addUserFollowing(Following $userFollowing): static
    {
        if (!$this->userFollowing->contains($userFollowing)) {
            $this->userFollowing->add($userFollowing);
            $userFollowing->setUserId($this);
        }

        return $this;
    }

    public function removeUserFollowing(Following $userFollowing): static
    {
        if ($this->userFollowing->removeElement($userFollowing)) {
            // set the owning side to null (unless already changed)
            if ($userFollowing->getUserId() === $this) {
                $userFollowing->setUserId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Followers>
     */
    public function getUserFollowers(): Collection
    {
        return $this->userFollowers;
    }

    public function addUserFollower(Followers $userFollower): static
    {
        if (!$this->userFollowers->contains($userFollower)) {
            $this->userFollowers->add($userFollower);
            $userFollower->setFollowerId($this);
        }

        return $this;
    }
//yeah
    public function removeUserFollower(Followers $userFollower): static
    {
        if ($this->userFollowers->removeElement($userFollower)) {
            // set the owning side to null (unless already changed)
            if ($userFollower->getFollowerId() === $this) {
                $userFollower->setFollowerId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Posts>
     */
    public function getUserPosts(): Collection
    {
        return $this->userPosts;
    }

    public function addUserPost(Posts $userPost): static
    {
        if (!$this->userPosts->contains($userPost)) {
            $this->userPosts->add($userPost);
            $userPost->setCreatorId($this);
        }

        return $this;
    }

    public function removeUserPost(Posts $userPost): static
    {
        if ($this->userPosts->removeElement($userPost)) {
            // set the owning side to null (unless already changed)
            if ($userPost->getCreatorId() === $this) {
                $userPost->setCreatorId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Comments>
     */
    public function getUserComments(): Collection
    {
        return $this->userComments;
    }

    public function addUserComment(Comments $userComment): static
    {
        if (!$this->userComments->contains($userComment)) {
            $this->userComments->add($userComment);
            $userComment->setUserId($this);
        }

        return $this;
    }

    public function removeUserComment(Comments $userComment): static
    {
        if ($this->userComments->removeElement($userComment)) {
            // set the owning side to null (unless already changed)
            if ($userComment->getUserId() === $this) {
                $userComment->setUserId(null);
            }
        }

        return $this;
    }
}
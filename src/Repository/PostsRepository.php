<?php
//
//namespace App\Entity;
//
//use App\Repository\PostsRepository;
//use Doctrine\Common\Collections\ArrayCollection;
//use Doctrine\Common\Collections\Collection;
//use Doctrine\ORM\Mapping as ORM;
//use Symfony\UX\Turbo\Attribute\Broadcast;
//
//#[ORM\Entity(repositoryClass: PostsRepository::class)]
//#[Broadcast]
//class Posts
//{
//    #[ORM\Id]
//    #[ORM\GeneratedValue]
//    #[ORM\Column]
//    private ?int $id = null;
//
//    #[ORM\ManyToOne(inversedBy: 'userPosts')]
//    #[ORM\JoinColumn(nullable: false)]
//    private ?User $creatorId = null;
//
//    #[ORM\ManyToOne(inversedBy: 'RecipePosts')]
//    private ?Recipes $recipeId = null;
//
//    /**
//     * @var Collection<int, Comments>
//     */
//    #[ORM\OneToMany(targetEntity: Comments::class, mappedBy: 'postId', orphanRemoval: true)]
//    private Collection $PostsComments;
//
//    public function __construct()
//    {
//        $this->PostsComments = new ArrayCollection();
//    }
//
//    public function getId(): ?int
//    {
//        return $this->id;
//    }
//
//    public function getCreatorId(): ?User
//    {
//        return $this->creatorId;
//    }
//
//    public function setCreatorId(?User $creatorId): static
//    {
//        $this->creatorId = $creatorId;
//
//        return $this;
//    }
//
//    public function getRecipeId(): ?Recipes
//    {
//        return $this->recipeId;
//    }
//
//    public function setRecipeId(?Recipes $recipeId): static
//    {
//        $this->recipeId = $recipeId;
//
//        return $this;
//    }
//
//    /**
//     * @return Collection<int, Comments>
//     */
//    public function getPostsComments(): Collection
//    {
//        return $this->PostsComments;
//    }
//
//    public function addPostsComment(Comments $postsComment): static
//    {
//        if (!$this->PostsComments->contains($postsComment)) {
//            $this->PostsComments->add($postsComment);
//            $postsComment->setPostId($this);
//        }
//
//        return $this;
//    }
//
//    public function removePostsComment(Comments $postsComment): static
//    {
//        if ($this->PostsComments->removeElement($postsComment)) {
//            // set the owning side to null (unless already changed)
//            if ($postsComment->getPostId() === $this) {
//                $postsComment->setPostId(null);
//            }
//        }
//
//        return $this;
//    }
//}
//

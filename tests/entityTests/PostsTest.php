<?php

namespace App\Tests\entityTests;

use App\Entity\Comments;
use App\Entity\Posts;
use App\Entity\Recipes;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class PostsTest extends TestCase
{
    public function testGetSetCreatorId(): void
    {
        $post = new Posts();
        $user = new User();

        $post->setCreatorId($user);
        $this->assertSame($user, $post->getCreatorId());

        // Test setting null
        $post->setCreatorId(null);
        $this->assertNull($post->getCreatorId());
    }

    public function testGetSetRecipeId(): void
    {
        $post = new Posts();
        $recipe = new Recipes();

        $post->setRecipeId($recipe);
        $this->assertSame($recipe, $post->getRecipeId());

        // Test setting null
        $post->setRecipeId(null);
        $this->assertNull($post->getRecipeId());
    }

    public function testAddRemovePostsComment(): void
    {
        $post = new Posts();
        $comment = new Comments();

        // Test adding comment
        $post->addPostsComment($comment);
        $this->assertCount(1, $post->getPostsComments());
        $this->assertTrue($post->getPostsComments()->contains($comment));

        // Test removing comment
        $post->removePostsComment($comment);
        $this->assertCount(0, $post->getPostsComments());
        $this->assertFalse($post->getPostsComments()->contains($comment));
    }

    public function testInvalidCreatorId(): void
    {
        $this->expectException(\TypeError::class);

        $post = new Posts();
        $post->setCreatorId("invalid_type"); // This should cause a TypeError
    }

    public function testInvalidRecipeId(): void
    {
        $this->expectException(\TypeError::class);

        $post = new Posts();
        $post->setRecipeId("invalid_type"); // This should cause a TypeError
    }

    public function testValidData(): void
    {
        $post = new Posts();
        $user = new User();
        $recipe = new Recipes();
        $comment1 = new Comments();
        $comment2 = new Comments();

        $post->setCreatorId($user);
        $post->setRecipeId($recipe);
        $post->addPostsComment($comment1);
        $post->addPostsComment($comment2);

        $this->assertSame($user, $post->getCreatorId());
        $this->assertSame($recipe, $post->getRecipeId());
        $this->assertCount(2, $post->getPostsComments());
        $this->assertTrue($post->getPostsComments()->contains($comment1));
        $this->assertTrue($post->getPostsComments()->contains($comment2));
    }

    public function testOverloadingData(): void
    {
        $post = new Posts();

        for ($i = 0; $i < 1000; $i++) {
            $comment = new Comments();
            $post->addPostsComment($comment);
        }

        $this->assertCount(1000, $post->getPostsComments());
    }

    public function testRemoveNonExistentComment(): void
    {
        $post = new Posts();
        $comment = new Comments();

        // Test removing a comment that was never added
        $post->removePostsComment($comment);
        $this->assertCount(0, $post->getPostsComments());
    }
}

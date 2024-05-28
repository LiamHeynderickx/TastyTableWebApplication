<?php
//
//namespace App\Tests\EntityTests;
//
//use App\Entity\Posts;
//use App\Entity\User;
//use App\Entity\Recipes;
//use App\Entity\Comments;
//use PHPUnit\Framework\TestCase;
//
//class PostsTest extends TestCase
//{
//    // Instantiate a Posts entity
//    public function testPostsInstantiation()
//    {
//        $post = new Posts();
//        $this->assertInstanceOf(Posts::class, $post);
//    }
//
//    // Test setting and getting the creatorId
//    public function testSetAndGetCreatorId()
//    {
//        $post = new Posts();
//        $user = new User();
//        $post->setCreatorId($user);
//        $this->assertSame($user, $post->getCreatorId());
//
//        // Testing that entering a non-User object returns error
//        $this->expectException(\TypeError::class);
//        $post->setCreatorId('invalid');
//    }
//
//    // Test setting and getting the recipeId
//    public function testSetAndGetRecipeId()
//    {
//        $post = new Posts();
//        $recipe = new Recipes();
//        $post->setRecipeId($recipe);
//        $this->assertSame($recipe, $post->getRecipeId());
//
//        // Testing that entering a non-Recipes object returns error
//        $this->expectException(\TypeError::class);
//        $post->setRecipeId('invalid');
//    }
//
//    // Test adding and getting posts comments
//    public function testAddAndGetPostsComments()
//    {
//        $post = new Posts();
//        $comment = new Comments();
//        $post->addPostsComment($comment);
//        $this->assertTrue($post->getPostsComments()->contains($comment));
//
//        // Testing that removing the comment works correctly (orphaning)
//        $post->removePostsComment($comment);
//        $this->assertFalse($post->getPostsComments()->contains($comment));
//    }
//
//    // Test that removing a comment sets the owning side to null (orphaning)
//    public function testRemovePostsComment()
//    {
//        $post = new Posts();
//        $comment = new Comments();
//        $post->addPostsComment($comment);
//        $post->removePostsComment($comment);
//
//        // Ensure the owning side is set to null
//        $this->assertNull($comment->getPost());
//    }
//}


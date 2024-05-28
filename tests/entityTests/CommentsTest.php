<?php

namespace App\Tests\EntityTests;

use App\Entity\Comments;
use App\Entity\User;
use App\Entity\Recipes;
use PHPUnit\Framework\TestCase;

class CommentsTest extends TestCase
{
    // Instantiate a Comments entity
    public function testCommentsInstantiation()
    {
        $comment = new Comments();
        $this->assertInstanceOf(Comments::class, $comment);
    }

    // Test setting and getting the userId
    public function testSetAndGetUserId()
    {
        $comment = new Comments();
        $user = new User();
        $comment->setUserId($user);
        $this->assertSame($user, $comment->getUserId());

        // Testing that entering a non-User object returns error
        $this->expectException(\TypeError::class);
        $comment->setUserId('invalid');
    }

    // Test setting and getting the recipeId
    public function testSetAndGetRecipeId()
    {
        $comment = new Comments();
        $recipe = new Recipes();
        $comment->setRecipeId($recipe);
        $this->assertSame($recipe, $comment->getRecipeId());

        // Testing that entering a non-Recipes object returns error
        $this->expectException(\TypeError::class);
        $comment->setRecipeId('invalid');
    }

    // Test setting and getting the comment
    public function testSetAndGetComment()
    {
        $comment = new Comments();
        $text = 'This is a test comment.';
        $comment->setComment($text);
        $this->assertSame($text, $comment->getComment());

        // Testing that entering a non-string object returns error
        $this->expectException(\TypeError::class);
        $comment->setComment(null);
    }

    // Test null entries
    public function testNullEntries()
    {
        $comment = new Comments();
        $this->expectException(\TypeError::class);
        $comment->setUserId(null);

        $this->expectException(\TypeError::class);
        $comment->setRecipeId(null);

        $this->expectException(\TypeError::class);
        $comment->setComment(null);
    }
}

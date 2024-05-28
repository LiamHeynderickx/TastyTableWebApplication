<?php

namespace App\Tests\EntityTests;

use App\Entity\Ratings;
use App\Entity\User;
use App\Entity\Recipes;
use PHPUnit\Framework\TestCase;

class RatingsTest extends TestCase
{
    public function testRatingsInitialization(): void
    {
        $rating = new Ratings();

        // Check initial values
        $this->assertNull($rating->getId(), 'Expected id to be null initially');
        $this->assertNull($rating->getUserId(), 'Expected userId to be null initially');
        $this->assertNull($rating->getRecipeId(), 'Expected recipeId to be null initially');
        $this->assertNull($rating->getRating(), 'Expected rating to be null initially');
    }

    public function testSetAndGetUserId(): void
    {
        $rating = new Ratings();
        $user = new User();

        // Test setting and getting userId
        $rating->setUserId($user);
        $this->assertSame($user, $rating->getUserId(), 'Expected userId to be instance of User');

        // Test setting invalid userId
        $this->expectException(\TypeError::class);
        $rating->setUserId('invalid');
    }

    public function testSetAndGetRecipeId(): void
    {
        $rating = new Ratings();
        $recipe = new Recipes();

        // Test setting and getting recipeId
        $rating->setRecipeId($recipe);
        $this->assertSame($recipe, $rating->getRecipeId(), 'Expected recipeId to be instance of Recipes');

        // Test setting invalid recipeId
        $this->expectException(\TypeError::class);
        $rating->setRecipeId('invalid');
    }

    public function testSetAndGetRating(): void
    {
        $rating = new Ratings();

        // Test setting and getting valid rating
        $rating->setRating(5);
        $this->assertSame(5, $rating->getRating(), 'Expected rating to be 5');

        // Test setting invalid rating
        $this->expectException(\TypeError::class);
        $rating->setRating('invalid');

        // Test setting null rating
        $rating->setRating(null);
        $this->assertNull($rating->getRating(), 'Expected rating to be null');
    }

    public function testSetRatingExceedingMaxValue(): void
    {
        $rating = new Ratings();

        // Test setting rating exceeding maximum value
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Rating cannot exceed 5.');
        $rating->setRating(6);
    }
}

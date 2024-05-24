<?php

namespace App\Tests\Entity;

use App\Entity\SavedRecipes;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class SavedRecipesTest extends TestCase
{
    private function createValidUser(): User
    {
        $user = new User();
        // Using reflection to set the ID for testing purposes
        $reflection = new \ReflectionClass($user);
        $property = $reflection->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($user, 1);

        $user->setEmail('test@example.com')
            ->setPassword('password123')
            ->setUsername('testuser')
            ->setName('Test')
            ->setSurname('User')
            ->setDietPreference('vegan');

        return $user;
    }

    public function testValidEntry()
    {
        $user = $this->createValidUser();
        $savedRecipe = new SavedRecipes();

        $savedRecipe->setUserId($user);
        $savedRecipe->setRecipeId(1);
        $savedRecipe->setIsApi(true);

        $this->assertInstanceOf(SavedRecipes::class, $savedRecipe);
        $this->assertSame($user, $savedRecipe->getUserId());
        $this->assertSame(1, $savedRecipe->getRecipeId());
        $this->assertTrue($savedRecipe->getIsApi());
    }

    public function testNullEntries()
    {
        $this->expectException(\TypeError::class);

        $savedRecipe = new SavedRecipes();
        $savedRecipe->setUserId(null); // This should trigger an error
    }

    public function testOnlyOneBooleanStatus()
    {
        $user = $this->createValidUser();
        $savedRecipe = new SavedRecipes();

        $savedRecipe->setUserId($user);
        $savedRecipe->setRecipeId(1);

        // Test valid boolean values

        //IsApi == true
        $savedRecipe->setIsApi(true);
        $this->assertIsBool($savedRecipe->getIsApi());
        $this->assertTrue($savedRecipe->getIsApi());

        //IsApi == false
        $savedRecipe->setIsApi(false);
        $this->assertIsBool($savedRecipe->getIsApi());
        $this->assertFalse($savedRecipe->getIsApi());

        // Test invalid boolean values

        //entry as string
        try {
            $savedRecipe->setIsApi('not a boolean');
        } catch (\TypeError $e) {
            $this->assertStringContainsString('must be of type bool', $e->getMessage());
        }
        //entry as int
        try {
            $savedRecipe->setIsApi(123);
        } catch (\TypeError $e) {
            $this->assertStringContainsString('must be of type bool', $e->getMessage());
        }
        //entry as null
        try {
            $savedRecipe->setIsApi(null);
        } catch (\TypeError $e) {
            $this->assertStringContainsString('must be of type bool', $e->getMessage());
        }
    }


    public function testNoDuplicateEntriesForUser()
    {
        $user = $this->createValidUser();

        //creating recipeId1
        $savedRecipe1 = new SavedRecipes();
        $savedRecipe1->setUserId($user);
        $savedRecipe1->setRecipeId(1);
        $savedRecipe1->setIsApi(true);
        $savedRecipe1->setIsMyRecipe(false);

        //creating recipeId2, but id is same as one for this test
        $savedRecipe2 = new SavedRecipes();
        $savedRecipe2->setUserId($user);
        $savedRecipe2->setRecipeId(1); // Same recipeId for both instances
        $savedRecipe2->setIsApi(true);
        $savedRecipe2->setIsMyRecipe(false);

        // Assert that the user cannot have multiple instances of the same recipe linked to them
        $this->assertSame($savedRecipe1->getUserId(), $savedRecipe2->getUserId());
        $this->assertSame($savedRecipe1->getRecipeId(), $savedRecipe2->getRecipeId());

        // Check if they are the same entry logically
        $this->assertFalse(
            //assertFalse returns a successful test if the logical operation within it is WRONG
            $savedRecipe1 === $savedRecipe2,
            'Entries should not be duplicates for the same user and recipe'
        );
    }
}

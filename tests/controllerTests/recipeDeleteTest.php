<?php

namespace App\Tests\controllerTests;

use App\Entity\Recipes;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class recipeDeleteTest extends WebTestCase {

    public function testDeleteExistingRecipe()
    {
        $client = static::createClient();

        // Create a user to own the recipe
        $user = new User();
        // Set necessary properties for the user
        $user->setName('user');
        $user->setSurname('surname');
        $user->setUsername('testuser');
        $user->setPassword('testpassword');
        $user->setEmail('testuser@example.com');

        // Persist and flush the user to the database
        $entityManager = $client->getContainer()->get('doctrine')->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        // Create a recipe to delete
        $recipe = new Recipes();

        // Set necessary properties
        $recipe->setRecipeName('Recipe to Delete');
        $recipe->setUserId($user->getId());
        $recipe->setCost('3');
        $recipe->setTime(30);
        $recipe->setServings(4);
        $recipe->setCalories(500);
        $recipe->setFat(20);
        $recipe->setCarbs(60);
        $recipe->setProtein(30);
        $recipe->setDiet('vegetarian');
        $recipe->setType('dinner');
        $recipe->setIngredients(['Ingredient 1', 'Ingredient 2']);
        $recipe->setIngredientsAmounts([100, 200]);
        $recipe->setIngredientsUnits(['g', 'ml']);
        $recipe->setInstructions(['Step 1: Do something', 'Step 2: Do something else']);


        // Persist and flush the recipe to the database
        $entityManager->persist($recipe);
        $entityManager->flush();

        // Get the ID of the created recipe
        $recipeId = $recipe->getId();

        // Make a request to delete the recipe
        $client->request('DELETE', '/recipe_delete/' . $recipeId);

        // Assertions
        $this->assertTrue($client->getResponse()->isRedirect('/homePage'));
        // Verify that the recipe is correctly deleted from the database
        $deletedRecipe = $entityManager->getRepository(Recipes::class)->find($recipeId);
        $this->assertNull($deletedRecipe);
    }

    public function testDeleteNonExistingRecipe()
    {
        $client = static::createClient();

        // Non-existing recipe ID
        $nonExistingId = 0;

        // Make a request to delete the recipe
        $client->request('GET', '/recipe_delete/' . $nonExistingId);

        // Assertions
        $this->assertTrue($client->getResponse()->isNotFound());
        // Check for the correct exception message or response content
        $this->assertSelectorTextContains('body', 'No recipe found for id ' . $nonExistingId);
    }

    public function testDeleteRecipeWithMismatchedUser()
    {
        $client = static::createClient();

        // Create two users
        $user1 = $this->createUser('testuser1', 'password1', 'user1@example.com');
        $user2 = $this->createUser('testuser2', 'password2', 'user2@example.com');

        // Persist and flush the users to the database
        $entityManager = $client->getContainer()->get('doctrine')->getManager();
        $entityManager->persist($user1);
        $entityManager->persist($user2);
        $entityManager->flush();

        // Create a recipe owned by user1
        $recipe = $this->createRecipe($user1, $entityManager);

        // Get the ID of the created recipe
        $recipeId = $recipe->getId();

        // Make a DELETE request to the recipe_delete endpoint as user2
        $client->request('DELETE', '/recipe_delete/' . $recipeId);

        // Assertions
        $this->assertEquals(403, $client->getResponse()->getStatusCode());
        // Verify that the recipe still exists in the database
        $updatedRecipe = $entityManager->getRepository(Recipes::class)->find($recipeId);
        $this->assertNotNull($updatedRecipe);
    }


    private function createUser(string $username, string $password, string $email): User
    {
        $user = new User();
        $user->setName('user');
        $user->setSurname('surname');
        $user->setUsername($username);
        $user->setPassword($password);
        $user->setEmail($email);

        return $user;
    }

    private function createRecipe(User $user, $entityManager): Recipes
    {
        $recipe = new Recipes();

        $recipe->setRecipeName('Recipe to Delete');
        $recipe->setUserId($user->getId());
        $recipe->setCost('3');
        $recipe->setTime(30);
        $recipe->setServings(4);
        $recipe->setCalories(500);
        $recipe->setFat(20);
        $recipe->setCarbs(60);
        $recipe->setProtein(30);
        $recipe->setDiet('vegetarian');
        $recipe->setType('dinner');
        $recipe->setIngredients(['Ingredient 1', 'Ingredient 2']);
        $recipe->setIngredientsAmounts([100, 200]);
        $recipe->setIngredientsUnits(['g', 'ml']);
        $recipe->setInstructions(['Step 1: Do something', 'Step 2: Do something else']);

        // Persist and flush the recipe to the database
        $entityManager->persist($recipe);
        $entityManager->flush();

        return $recipe;
    }

    public function testDeleteRecipeWhenNotLoggedIn()
    {
        $client = static::createClient();

        // Create a user to own the recipe
        $user = $this->createUser('testuser', 'password', 'testuser@example.com');

        // Persist and flush the user to the database
        $entityManager = $client->getContainer()->get('doctrine')->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        // Create a recipe owned by the user
        $recipe = $this->createRecipe($user, $entityManager);

        // Get the ID of the created recipe
        $recipeId = $recipe->getId();

        // Make a DELETE request to the recipe_delete endpoint without logging in
        $client->request('DELETE', '/recipe_delete/' . $recipeId);

        // Assertions
        $this->assertTrue($client->getResponse()->isRedirect('/'));
        // Verify that the recipe is not deleted from the database
        $existingRecipe = $entityManager->getRepository(Recipes::class)->find($recipeId);
        $this->assertNotNull($existingRecipe);
    }
}

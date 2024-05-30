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

}

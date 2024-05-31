<?php

namespace App\Tests\controllerTests;

use App\Entity\Recipes;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class recipeEditTest extends WebTestCase {

    public function testLoadEditForm()
    {
        $client = static::createClient();

        // Create a recipe to edit (you may need to adjust based on your entity setup)
        $recipe = new Recipes();
        // Set necessary properties

        $recipe->setUserId('0');
        $recipe->setRecipeName('Existing Recipe');
        $recipe->setRecipeDescription('An existing description');
        $recipe->setCost('2');
        $recipe->setTime(45);
        $recipe->setServings(6);
        $recipe->setCalories(400);
        $recipe->setFat(20);
        $recipe->setCarbs(50);
        $recipe->setProtein(25);
        $recipe->setDiet('vegan');
        $recipe->setType('lunch');
        $recipe->setIngredients(['Existing Ingredient 1', 'Existinh Ingredient 2']);
        $recipe->setIngredientsAmounts([150, 250]);
        $recipe->setIngredientsUnits(['g', 'g']);
        $recipe->setInstructions(['Step 1: Existing step', 'Step 2: Existing step']);

        //file upload is tested in another function

        // Persist and flush the recipe to the database
        $entityManager = $client->getContainer()->get('doctrine')->getManager();
        $entityManager->persist($recipe);
        $entityManager->flush();

        // Get the ID of the created recipe
        $recipeId = $recipe->getId();

        // Make a request to load the edit form
        $crawler = $client->request('GET', '/recipe_edit/' . $recipeId);

        // Assertions
        $this->assertResponseIsSuccessful();
        // Check that form fields are pre-filled with existing recipe data
        $this->assertEquals('Existing Recipe', $crawler->filter('input[name="form[recipeName]"]')->attr('value'));
        $this->assertEquals('An updated description', $crawler->filter('textarea[name="form[recipeDescription]"]')->text());
        $this->assertEquals('2', $crawler->filter('input[name="form[cost]"]:checked')->attr('value'));
        $this->assertEquals('45', $crawler->filter('input[name="form[time]"]')->attr('value'));
        $this->assertEquals('400', $crawler->filter('input[name="form[calories]"]')->attr('value'));
        $this->assertEquals('20', $crawler->filter('input[name="form[fat]"]')->attr('value'));
        $this->assertEquals('50', $crawler->filter('input[name="form[carbs]"]')->attr('value'));
        $this->assertEquals('25', $crawler->filter('input[name="form[protein]"]')->attr('value'));
        $this->assertEquals('6', $crawler->filter('input[name="form[servings]"]')->attr('value'));
        $this->assertEquals('vegan', $crawler->filter('select[name="form[diet]"] option[selected]')->attr('value'));
        $this->assertEquals('lunch', $crawler->filter('select[name="form[type]"] option[selected]')->attr('value'));

        // Check ingredients, amounts, and units
        $this->assertEquals('Updated Ingredient 1', trim($crawler->filter('.ingredient-1')->text()));
        $this->assertEquals('Updated Ingredient 2', trim($crawler->filter('.ingredient-2')->text()));
        $this->assertEquals('150', trim($crawler->filter('.ingredient-1-amount')->text()));
        $this->assertEquals('250', trim($crawler->filter('.ingredient-2-amount')->text()));
        $this->assertEquals('g', trim($crawler->filter('.ingredient-1-unit')->text()));
        $this->assertEquals('g', trim($crawler->filter('.ingredient-2-unit')->text()));

        // Check instructions (assuming they are displayed in a certain format)
        $this->assertEquals('Step 1: Updated step', trim($crawler->filter('.instruction-1')->text()));
        $this->assertEquals('Step 2: Updated step', trim($crawler->filter('.instruction-2')->text()));
    }

    public function testSuccessfulRecipeEdit()
    {
        $client = static::createClient();

        // Create a recipe to edit (you may need to adjust based on your entity setup)
        $recipe = new Recipes();
        $recipe->setUserId('0');
        $recipe->setRecipeName('Existing Recipe');
        $recipe->setRecipeDescription('An existing description');
        $recipe->setCost('2');
        $recipe->setTime(45);
        $recipe->setServings(6);
        $recipe->setCalories(400);
        $recipe->setFat(20);
        $recipe->setCarbs(50);
        $recipe->setProtein(25);
        $recipe->setDiet('vegan');
        $recipe->setType('lunch');
        $recipe->setIngredients(['Existing Ingredient 1', 'Existing Ingredient 2']);
        $recipe->setIngredientsAmounts([150, 250]);
        $recipe->setIngredientsUnits(['g', 'g']);
        $recipe->setInstructions(['Step 1: Existing step', 'Step 2: Existing step']);

        // Persist and flush the recipe to the database
        $entityManager = $client->getContainer()->get('doctrine')->getManager();
        $entityManager->persist($recipe);
        $entityManager->flush();

        // Get the ID of the created recipe
        $recipeId = $recipe->getId();

        // Make a request to load the edit form
        $crawler = $client->request('GET', '/recipe_edit/' . $recipeId);

        // Fill in the form fields with updated data
        $form = $crawler->selectButton('Submit Recipe')->form();
        $form['form[recipeName]'] = 'Updated Recipe';

        // Submit the form
        $client->submit($form);

        // Assertions
        $this->assertTrue($client->getResponse()->isRedirect('/recipeDisplay/' . $recipeId));
        // Verify that the recipe is correctly updated in the database
        $updatedRecipe = $entityManager->getRepository(Recipes::class)->find($recipeId);
        $this->assertEquals('Updated Recipe', $updatedRecipe->getRecipeName());
    }

    public function testFileUploadDuringEdit()
    {
        $client = static::createClient();

        // Create a recipe to edit
        $recipe = new Recipes();
        // Set necessary properties
        $recipe->setUserId('0');
        $recipe->setRecipeName('Existing Recipe');
        $recipe->setRecipeDescription('An existing description');
        $recipe->setCost('2');
        $recipe->setTime(45);
        $recipe->setServings(6);
        $recipe->setCalories(400);
        $recipe->setFat(20);
        $recipe->setCarbs(50);
        $recipe->setProtein(25);
        $recipe->setDiet('vegan');
        $recipe->setType('lunch');
        $recipe->setIngredients(['Existing Ingredient 1', 'Existing Ingredient 2']);
        $recipe->setIngredientsAmounts([150, 250]);
        $recipe->setIngredientsUnits(['g', 'g']);
        $recipe->setInstructions(['Step 1: Existing step', 'Step 2: Existing step']);

        // Persist and flush the recipe to the database
        $entityManager = $client->getContainer()->get('doctrine')->getManager();
        $entityManager->persist($recipe);
        $entityManager->flush();

        // Get the ID of the created recipe
        $recipeId = $recipe->getId();

        // Make a request to load the edit form
        $crawler = $client->request('GET', '/recipe_edit/' . $recipeId);

        // Path to a real image file for testing
        $testFilePath = __DIR__ . '/test.jpg';
        copy($testFilePath, sys_get_temp_dir() . '/test.jpg'); // Copy to a temp directory to simulate an upload
        $uploadedFile = new UploadedFile(
            sys_get_temp_dir() . '/test.jpg',
            'test.jpg',
            'image/jpeg',
            null,
            true // Set to true if the file is already moved to a temp directory
        );

        // Simulate file upload
        $form = $crawler->selectButton('Submit Recipe')->form();
        $form['form[picture]']->upload($uploadedFile);

        // Submit the form
        $client->submit($form);

        // Assertions
        $this->assertTrue($client->getResponse()->isRedirect('/recipeDisplay/' . $recipeId));

        // Follow the redirect
        $client->followRedirect();

        // Refresh the entity manager to get the updated data
        $entityManager->clear();
        $updatedRecipe = $entityManager->getRepository(Recipes::class)->find($recipeId);

        // Verify that the file path in the database is not null and corresponds to an existing file
        $this->assertNotNull($updatedRecipe->getPicturePath());

        // Verify that the file exists in the expected directory
        $uploadedFilePath = 'public/style/images/recipeImages/' . basename($updatedRecipe->getPicturePath());
        $this->assertFileExists($uploadedFilePath);
    }


    public function testEditNonExistingRecipe()
    {
        $client = static::createClient();

        // Provide a non-existing recipe ID
        $nonExistingId = 9999;

        // Make a request to edit the recipe
        $client->request('GET', '/recipe_edit/' . $nonExistingId);

        // Assertions
        $this->assertTrue($client->getResponse()->isNotFound());
        // Check for the correct exception message or response content
        $this->assertSelectorTextContains('body', 'No recipe found for id ' . $nonExistingId);
    }

}
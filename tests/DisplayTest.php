<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Recipes;

class DisplayTest extends WebTestCase
{
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->entityManager = self::$container->get(EntityManagerInterface::class);
    }

    public function testDisplayWorks(): void
    {
        // Create a fake recipe in the database for testing
        $recipe = new Recipes();
        $recipe->setUserId(1);
        $recipe->setRecipeName('Test Recipe');
        $recipe->setRecipeDescription('Test Description');
        $recipe->setCost(10);
        $recipe->setIngredients(['ingredient1', 'ingredient2']);
        $recipe->setIngredientsAmounts([100, 200]);
        $recipe->setIngredientsUnits(['grams', 'ml']);
        $recipe->setInstructions(['Step 1', 'Step 2']);
        $recipe->setTime(30);
        $recipe->setCalories(200);
        $recipe->setFat(10);
        $recipe->setCarbs(30);
        $recipe->setProtein(20);
        $recipe->setServings(2);
        $recipe->setDiet('Vegetarian');
        $recipe->setType('Dinner');

        $this->entityManager->persist($recipe);
        $this->entityManager->flush();

        // Test the display of the recipe
        $client = static::createClient();
        $crawler = $client->request('GET', '/recipeDisplay/' . $recipe->getId());

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Test Recipe');
        $this->assertSelectorTextContains('p', 'Test Description');
    }

    public function testDisplayRecipeNotFound(): void
    {
        // Test the display of a non-existing recipe
        $client = static::createClient();
        $nonExistentId = 999999; // Assume this ID does not exist
        $crawler = $client->request('GET', '/recipeDisplay/' . $nonExistentId);

        $response = $client->getResponse();

        // Assuming your controller returns a 404 status for not found recipes
        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
        $this->assertSelectorTextContains('body', 'The recipe does not exist');
    }
}

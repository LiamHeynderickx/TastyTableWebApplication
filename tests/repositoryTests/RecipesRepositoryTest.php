<?php

namespace App\Tests\repositoryTests;

use App\Entity\Recipes;
use App\Repository\RecipesRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class RecipesRepositoryTest extends KernelTestCase
{
    private $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
        $this->entityManager = null; // avoid memory leaks
    }

    public function testFindIdsByUserId(): void
    {
        $recipesRepository = $this->entityManager->getRepository(Recipes::class);
        $this->assertInstanceOf(RecipesRepository::class, $recipesRepository);

        $userId = 9; // must be existing userID, otherwise test fails
        $ids = $recipesRepository->findIdsByUserId($userId); //bc multiple recipeID for one userID possible

        // Check that the result is an array
        $this->assertIsArray($ids);

        // Check that the array is not empty
        $this->assertNotEmpty($ids, 'Expected to find recipe IDs, but no IDs were found.');

        // Further assertions based on expected data
        foreach ($ids as $id) {
            // Extract the ID from the associative array
            $this->assertArrayHasKey('id', $id, 'Expected the key "id" to be present in the array.');
            $this->assertIsInt($id['id'], 'Expected ID to be an integer.');
        }
    }

    public function testFindRecipesByIdsAndDiets(): void
    {
        $recipesRepository = $this->entityManager->getRepository(Recipes::class);

        $recipeIds = [7, 46]; // Specific recipe IDs to test, if not present then test fails
        $diets = ['vegetarian', 'vegan'];

        // Call the method with actual data
        $filteredRecipes = $recipesRepository->findRecipesByIdsAndDiets($recipeIds, $diets);

        // Check that the result is an array
        $this->assertIsArray($filteredRecipes); //array of data returned which matches conditions we set above

        // Check that the array is not empty
        $this->assertNotEmpty($filteredRecipes, 'Expected to find recipes, but no recipes were found.');

        foreach ($filteredRecipes as $recipe) {
            $this->assertInstanceOf(Recipes::class, $recipe);
            $this->assertContains(strtolower($recipe->getDiet()), $diets);
            $this->assertTrue(in_array($recipe->getId(), $recipeIds), 'Recipe ID should be in the list of provided IDs');
        }
    }



    public function testFindRecipesByName(): void
    {
        $recipesRepository = $this->entityManager->getRepository(Recipes::class);

        $name = 'chug jug';
        $recipes = $recipesRepository->findRecipesByName($name);

        // Assert that the result is an array
        $this->assertIsArray($recipes);

        // Check if we have any results
        $this->assertNotEmpty($recipes, 'Expected to find recipes, but no recipes were found.');

        foreach ($recipes as $recipe) {
            $this->assertInstanceOf(Recipes::class, $recipe);
            $this->assertStringContainsString($name, $recipe->getRecipeName());
        }
    }

    // Test for SQL Injection
    public function testPreventSqlInjectionInFindRecipesByName(): void
    {
        $recipesRepository = $this->entityManager->getRepository(Recipes::class);

        $injectionString = "'; DROP TABLE recipes; --"; //if protected against SQL injects, findRecipesByName will only read it as string, not sql code
        $recipes = $recipesRepository->findRecipesByName($injectionString);

        $this->assertIsArray($recipes);
        foreach ($recipes as $recipe) {
            $this->assertInstanceOf(Recipes::class, $recipe); //asserts results is an array
        }
    }

    public function testPreventSqlInjectionInFindRecipesByIdsAndDiets(): void
    {
        $recipesRepository = $this->entityManager->getRepository(Recipes::class);

        $recipeIds = [1, 2, 3]; // Assuming these recipe IDs exist
        $diets = ["vegetarian'; DROP TABLE recipes; --"]; //make sure this is not read as sql, onle read string
        $recipes = $recipesRepository->findRecipesByIdsAndDiets($recipeIds, $diets);

        $this->assertIsArray($recipes);
        foreach ($recipes as $recipe) {
            $this->assertInstanceOf(Recipes::class, $recipe);
        }
    }
}

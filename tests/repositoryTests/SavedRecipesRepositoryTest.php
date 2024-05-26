<?php

namespace App\Tests\repositoryTests;

use App\Entity\SavedRecipes;
use App\Repository\SavedRecipesRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class SavedRecipesRepositoryTest extends KernelTestCase
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

    public function testEnvironment(): void
    {
        $kernel = self::bootKernel();
        $this->assertSame('test', $kernel->getEnvironment());
    }

    public function testSavedRecipesRepository(): void
    {
        $savedRecipesRepository = $this->entityManager->getRepository(SavedRecipes::class);
        $this->assertInstanceOf(SavedRecipesRepository::class, $savedRecipesRepository);
    }

    public function testFindRecipeIdsByUserAndIsApiWithValidParameters(): void
    {
        $savedRecipesRepository = $this->entityManager->getRepository(SavedRecipes::class);

        // Assume that user with ID 1 exists and has saved recipes
        $recipeIds = $savedRecipesRepository->findRecipeIdsByUserAndIsApi(1, 1, 0);

        $this->assertIsArray($recipeIds);
        // Further assertions can be made based on expected results
    }

    public function testFindRecipeIdsByUserAndIsApiWithInvalidUserId(): void
    {
        $this->expectException(\TypeError::class);

        $savedRecipesRepository = $this->entityManager->getRepository(SavedRecipes::class);

        // Invalid userId
        $savedRecipesRepository->findRecipeIdsByUserAndIsApi('invalid', 1, 0);
    }

    public function testFindRecipeIdsByUserAndIsApiWithInvalidIsApi(): void
    {
        $this->expectException(\TypeError::class);

        $savedRecipesRepository = $this->entityManager->getRepository(SavedRecipes::class);

        // Invalid isApi
        $savedRecipesRepository->findRecipeIdsByUserAndIsApi(1, 'invalid', 0);
    }

    public function testFindRecipeIdsByUserAndIsApiWithInvalidIsMyRecipe(): void
    {
        $this->expectException(\TypeError::class);

        $savedRecipesRepository = $this->entityManager->getRepository(SavedRecipes::class);

        // Invalid isMyRecipe
        $savedRecipesRepository->findRecipeIdsByUserAndIsApi(1, 1, 'invalid');
    }
}

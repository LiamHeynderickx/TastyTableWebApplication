<?php

namespace App\Tests\Controller;

use App\Controller\TastyTableController; // Replace with the actual namespace and class name
use App\Entity\Recipes;
use App\Repository\RecipesRepository; // Make sure to import the correct repository class
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class HomePageFilteredTest extends KernelTestCase
{
    public function testHomePageFiltered()
    {
        // Boot the Symfony kernel
        self::bootKernel();

        // Get the container
        $container = self::$kernel->getContainer();

        // Mock the Request object
        $request = $this->createMock(Request::class);
        $query = new InputBag([
            'vegetarian' => 'on',
            'gluten-free' => 'on',
        ]);
        $request->query = $query;

        // Define the expected filter criteria
        $filters = [
            'vegetarian' => 'on',
            'vegan' => null,
            'gluten-free' => 'on',
            'dairy-free' => null
        ];

        // Mock the EntityManager and the repository
        $recipeRepo = $this->createMock(RecipesRepository::class);
        $recipeRepo->method('getFilteredRecipes')->with($filters)->willReturn($this->getSampleRecipes());

        $em = $this->createMock(EntityManagerInterface::class);
        $em->method('getRepository')->with(Recipes::class)->willReturn($recipeRepo);

        // Mock the Twig environment
        $twig = $this->createMock(Environment::class);
        $twig->method('render')->willReturnCallback(function ($view, $params) {
            return new Response(''); // Ensure it returns a Response object
        });

        // Instantiate the controller with the container
        $controller = new TastyTableController($twig, $em);
        $controller->setContainer($container);

        // Call the method
        $response = $controller->homePageFiltered($request, $em);

        // Assert that the response is successful
        $this->assertEquals(200, $response->getStatusCode());

        // Additional assertions can be made here to verify the contents of the response
    }

    private function getSampleRecipes()
    {
        // Return an array of sample recipes, you can adjust this data as needed
        return [
            new Recipes(),
            new Recipes(),
            new Recipes(),
            new Recipes(),
            new Recipes(),
            new Recipes(),
            new Recipes(),
            new Recipes(),
            new Recipes(),
            new Recipes(), // Add more if necessary
        ];
    }
}

<?php

namespace App\Tests\controllerTests;

use App\Entity\Comments;
use App\Entity\Recipes;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use App\Service\SpoonacularApiService;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

class recipeDisplayTest extends WebTestCase
{
    public function testValidRecipeIdExistsInDatabase()
    {
        $client = static::createClient();
        $client->enableProfiler();

        // Mock the session service
        $mockSession = $this->createMock(SessionInterface::class);
        $mockSession->method('get')->willReturnMap([
            ['isOnline', true],
            ['userId', 1],
        ]);

        // Set the mocked session in the container
        $client->getContainer()->set('session', $mockSession);

        // Display a request with a valid recipe ID
        $crawler = $client->request('GET', '/recipeDisplay/7');

        // Check if response is a redirect
        $this->assertTrue($client->getResponse()->isRedirect());

        $client->followRedirect();

        // Page loads successfully
        $this->assertResponseIsSuccessful();

        // Hard to do tests in a mock session, but could not figure out how to setup a session in test

    }


    public function testInvalidRecipeIdRedirectsToHomepage()
    {
        $client = static::createClient();
        $client->enableProfiler();

        // Mock the session service
        $mockSession = $this->createMock(SessionInterface::class);
        $mockSession->method('get')->willReturnMap([
            ['isOnline', true],
        ]);

        // Set the mocked session in the container
        $client->getContainer()->set('session', $mockSession);

        // Make a request with an invalid recipe ID
        $crawler = $client->request('GET', '/recipeDisplay/0');

        $this->assertResponseRedirects('/');
    }

    public function testRecipeFetchedFromApi()
    {
        $client = static::createClient();
        $client->enableProfiler();

        // Mock the API service to return a specific recipe
        $apiService = $this->getMockBuilder(SpoonacularApiService::class)
            ->disableOriginalConstructor()
            ->getMock();
        $apiService->method('getRecipeByIdFordisplay')
            ->willReturn(['id' => 123, 'title' => 'Mock Recipe']);

        // Set the mocked service in the container
        $client->getContainer()->set(SpoonacularApiService::class, $apiService);

        // Mock the session service
        $mockSession = $this->createMock(SessionInterface::class);
        $mockSession->method('get')->willReturnMap([
            ['isOnline', true],
        ]);

        // Set the mocked session in the container
        $client->getContainer()->set('session', $mockSession);

        // Make a request with a valid recipe ID that triggers API fetch
        $crawler = $client->request('GET', '/recipeDisplay/123');

        // Check if response is a redirect
        $this->assertTrue($client->getResponse()->isRedirect());

        $client->followRedirect();

        // Assert the response is successful (HTTP 200)
        $this->assertResponseIsSuccessful();

    }


    public function testCommentSubmission()
    {
        $client = static::createClient();

        // Create a user to own the recipe and submit the comment
        $user = new User();
        $user->setName('Test');
        $user->setSurname('User');
        $user->setUsername('testuser');
        $user->setPassword(password_hash('testpassword', PASSWORD_BCRYPT));
        $user->setEmail('testuser@example.com');

        // Persist and flush the user to the database
        $entityManager = $client->getContainer()->get('doctrine')->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        // Create a recipe for the test
        $recipe = new Recipes();
        $recipe->setRecipeName('Test Recipe');
        $recipe->setUserId($user->getId());
        $recipe->setCost('5');
        $recipe->setTime(45);
        $recipe->setServings(4);
        $recipe->setCalories(600);
        $recipe->setFat(25);
        $recipe->setCarbs(70);
        $recipe->setProtein(35);
        $recipe->setDiet('vegan');
        $recipe->setType('dinner');
        $recipe->setIngredients(['Ingredient A', 'Ingredient B']);
        $recipe->setIngredientsAmounts([150, 250]);
        $recipe->setIngredientsUnits(['g', 'ml']);
        $recipe->setInstructions(['Step 1: Prepare ingredients', 'Step 2: Cook ingredients']);

        // Persist and flush the recipe to the database
        $entityManager->persist($recipe);
        $entityManager->flush();

        // Get the ID of the created recipe
        $recipeId = $recipe->getId();

        // Mock the session to simulate a logged-in user
        $session = new Session(new MockArraySessionStorage());
        $session->set('isOnline', true);
        $session->set('userId', $user->getId());

        // Set the session into the client
        $client->getContainer()->set('session', $session);
        $client->getContainer()->get('session')->setId('mock-session-id');

        // Simulate a request to load the recipe display page
        $crawler = $client->request('GET', '/recipeDisplay/' . $recipeId);

        // Check if the response is successful
        $this->assertResponseIsSuccessful();

        // Ensure the URI is correct
        $this->assertEquals('/recipeDisplay/' . $recipeId, $client->getRequest()->getRequestUri());

        // Check if the form exists and submit the comment
        $form = $crawler->selectButton('Add Comment')->form();
        $form['comment'] = 'This is a test comment.';
        $client->submit($form);

        // Assertions
        $this->assertResponseRedirects('/recipeDisplay/' . $recipeId);

        // Follow the redirect and check the response
        $crawler = $client->followRedirect();
        $this->assertResponseIsSuccessful();

        // Check if the comment appears on the page
        $this->assertSelectorTextContains('li', 'This is a test comment.');

        // Verify that the comment is correctly saved to the database
        $comment = $entityManager->getRepository(Comments::class)->findOneBy(['comment' => 'This is a test comment.']);
        $this->assertNotNull($comment);
        $this->assertEquals($user->getId(), $comment->getUserId()->getId());
        $this->assertEquals($recipe->getId(), $comment->getRecipeId()->getId());
    }

    public function testAccessDeniedForUnauthenticatedUser()
    {
        $client = static::createClient();

        // Make a request to the recipeSubmission endpoint
        $crawler = $client->request('GET', '/recipeSubmission');

        // Assert that the response is a redirect
        $this->assertTrue($client->getResponse()->isRedirect());

        // Assert that the redirect is to the correct URL (http://127.0.0.1:8000/)
        $this->assertEquals('/', $client->getResponse()->getTargetUrl());
    }

}

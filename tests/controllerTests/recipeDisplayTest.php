<?php

namespace App\Tests\controllerTests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use App\Service\SpoonacularApiService;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

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

        // Make a request with a valid recipe ID
        $crawler = $client->request('GET', '/recipeDisplay/7');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Recipe Details');
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

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Mock Recipe');
    }

    public function testCommentSubmission()
    {
        $client = static::createClient();
        $client->enableProfiler();

        // Mock the session service
        $mockSession = $this->createMock(SessionInterface::class);
        $mockSession->method('get')->willReturnMap([
            ['isOnline', true],
            ['userId', 1], // Adjust as per your session mock requirements
        ]);

        // Set the mocked session in the container
        $client->getContainer()->set('session', $mockSession);

        // Simulate a request to load the page where comment submission form is present
        $crawler = $client->request('GET', '/recipeDisplay/7');

        // Check if the form exists
        $form = $crawler->filter('form[name="comment_form"]')->form();
        $this->assertCount(1, $form, 'Form not found on the page.');

        // Simulate form submission with comment data
        $form['comment'] = 'This is a test comment.';
        $client->submit($form);

        // Assertions
        $this->assertResponseRedirects('/recipeDisplay/7'); // Ensure redirect back to the recipe page
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

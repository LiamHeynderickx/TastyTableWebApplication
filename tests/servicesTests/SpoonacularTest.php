<?php

namespace App\Tests\servicesTests;

use App\Service\SpoonacularApiService;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class SpoonacularTest extends WebTestCase
{
    private $client;
    private $service;

    protected function setUp(): void
    {
        $this->client = $this->createMock(HttpClientInterface::class);
        $this->service = new SpoonacularApiService($this->client);
    }

//    public function testGetRandomRecipe(): void
//    {
//        // Mock the first API response for the initial search with no results
//        $response1 = $this->createMock(ResponseInterface::class);
//        $response1->method('getContent')->willReturn(json_encode([
//            'results' => []
//        ]));
//
//        // Configure the client mock to return the response
//        $this->client->expects($this->once())
//            ->method('request')
//            ->with($this->equalTo('GET'), $this->stringContains('/recipes/complexSearch'))
//            ->willReturn($response1);
//
//        // Set all filters to ensure no recipe is found
//        $filters = [
//            'vegetarian' => true,
//            'vegan' => true,
//            'gluten-free' => true,
//            'dairy-free' => true
//        ];
//
//        // Call the method under test
//        $result = $this->service->getRandomRecipe($filters);
//
//        // Print the id to the terminal
//        fwrite(STDERR, print_r($result['id'], true));
//
//        // Assert that the result has the expected default values
//        $this->assertIsArray($result);
//        $this->assertEquals('No title available', $result['title']);
//        $this->assertEquals('default_image.png', $result['image']);
//        $this->assertEquals('Unknown', $result['time']);
//        $this->assertEquals('No rating', $result['score']);
//        $this->assertNull($result['id']);
//    }


    public function testGetRecipeById(): void
    {
        // Mock the API response for getting recipe information
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getContent')->willReturn(json_encode([
            'title' => 'Spiced Apple Cider',
            'image' => 'https://img.spoonacular.com/recipes/660932-556x370.jpg',
            'id' => 660932,
            'servings' => 6
        ]));

        // Configure the client mock to return the response
        $this->client->method('request')
            ->with($this->equalTo('GET'), $this->stringContains('/recipes/660932/information'))
            ->willReturn($response);

        // Call the method under test
        $result = $this->service->getRecipeById(660932);

        // Assert that the result has the expected values
        $this->assertIsArray($result);
        $this->assertEquals('Spiced Apple Cider', $result['title']);
        $this->assertEquals('https://img.spoonacular.com/recipes/660932-556x370.jpg', $result['image']);
        $this->assertEquals(660932, $result['id']);
        $this->assertEquals(6, $result['servings']);
    }
//
    public function testSearchRecipesByName(): void
    {
        // Mock the API response for searching recipes by name
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getContent')->willReturn(json_encode([
            'results' => [
                ['title' => 'Spiced Apple Cider', 'image' => 'https://img.spoonacular.com/recipes/660932-312x231.jpg', 'id' => 660932]
            ]
        ]));

        // Configure the client mock to return the response
        $this->client->method('request')
            ->with($this->equalTo('GET'), $this->stringContains('/recipes/complexSearch'))
            ->willReturn($response);

        // Call the method under test
        $result = $this->service->searchRecipesByName('spiced apple cider');

        // Assert that the result has the expected values
        //$this->assertCount(1, $result);
        $this->assertEquals('Spiced Apple Cider', $result[0]['title']);
        $this->assertEquals('https://img.spoonacular.com/recipes/660932-312x231.jpg', $result[0]['image']);
        $this->assertEquals(660932, $result[0]['id']);
    }

    public function testGetScoreStars()
    {
        // Mock the HttpClientInterface
        $httpClientMock = $this->createMock(HttpClientInterface::class);
        $spoonacularApiService = new SpoonacularApiService($httpClientMock);

        // Use reflection to access the private getScoreStars method
        $reflection = new \ReflectionClass($spoonacularApiService);
        $method = $reflection->getMethod('getScoreStars');

        // Test case for a score of 10, which should return one star
        $score = 10;
        $expectedStars = "★☆☆☆☆";

        // Invoke the private method and get the result
        $result = $method->invokeArgs($spoonacularApiService, [$score]);

        // Assert that the result is as expected
        $this->assertEquals($expectedStars, $result);
    }

//    public function testGetRecipesInformationBulk(): void
//    {
//        $response = $this->createMock(ResponseInterface::class);
//        $response->method('getContent')->willReturn(json_encode([
//            ['id' => 1, 'title' => 'Mock Recipe 1'],
//            ['id' => 2, 'title' => 'Mock Recipe 2']
//        ]));
//        $response->method('getStatusCode')->willReturn(200);
//        $this->client->method('request')->willReturn($response);
//
//        $result = $this->service->getRecipesInformationBulk([1, 2]);
//
//        $this->assertCount(2, $result);
//        $this->assertEquals(1, $result[0]['id']);
//        $this->assertEquals('Mock Recipe 1', $result[0]['title']);
//        $this->assertEquals(2, $result[1]['id']);
//        $this->assertEquals('Mock Recipe 2', $result[1]['title']);
//    }
}





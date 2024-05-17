<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class SpoonacularApiService
{
    private $client;
    private string $apiKey;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
        $this->apiKey = '19d88678c40e403bae96298037a292bc';
        //$this->apiKey =   'c032d39ece4346bdb75d5e9ac3d6b903';
    }

    public function searchRecipesByNutrients(array $params)
    {
        $response = $this->client->request(
            'GET',
            'https://api.spoonacular.com/recipes/findByNutrients',
            [
                'query' => array_merge($params, ['apiKey' => $this->apiKey])
            ]
        );

        if ($response->getStatusCode() !== 200) {
            throw new \Exception('API request failed.');
        }

        return $response->toArray();
    }
/*
    public function getRecipesByIds(array $ids)
    {
        // Convert the array of IDs into a comma-separated string
        $idsString = implode(',', $ids);

        // Make the API request
        $response = $this->client->request(
            'GET',
            'https://api.spoonacular.com/recipes/informationBulk',
            [
                'query' => [
                    'ids' => $idsString,
                    'apiKey' => $this->apiKey
                ]
            ]
        );

        // Check if the response status code is not 200
        if ($response->getStatusCode() !== 200) {
            throw new \Exception('API request failed.');
        }

        // Return the response data as an array
        return $response->toArray();
    }
*/

    public function getRecipesInformationBulk(array $recipeIds)
    {
        $response = $this->client->request(
            'GET',
            'https://api.spoonacular.com/recipes/informationBulk',
            [
                'query' => [
                    'ids' => implode(',', $recipeIds),
                    'apiKey' => $this->apiKey
                ]
            ]
        );

        if ($response->getStatusCode() !== 200) {
            throw new \Exception('API request failed.');
        }

        return $response->toArray();
    }


}

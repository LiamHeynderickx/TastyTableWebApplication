<?php
namespace App\Service;

use phpDocumentor\Reflection\Types\This;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class SpoonacularApiService
{
    private $client;
    private string $apiKey;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
        $this->apiKey = '19d88678c40e403bae96298037a292bc';
        $this->apiKey =   'c032d39ece4346bdb75d5e9ac3d6b903';
        $this->apiKey = 'a97f080d485740608c87a17ef0957691';
    }

    public function getRandomRecipe() {
        $urlRandom = "https://api.spoonacular.com/recipes/random?number=1&apiKey=" . $this->apiKey;
        $response = file_get_contents($urlRandom);

        // Decode the JSON response into a stdClass object
        $data = json_decode($response);

        if(!isset($data->recipes[0]->image)){ //overwrite image with default
            $image = $data->recipes[0]->image ?? 'style/images/WebTech Mascot.jpg';
            return array($data->recipes[0]->title, $image, $data->recipes[0]->readyInMinutes,
                intval($data->recipes[0]->spoonacularScore), $data->recipes[0]->id);
        }
        else{
            return array($data->recipes[0]->title, $data->recipes[0]->image,
                $data->recipes[0]->readyInMinutes, intval($data->recipes[0]->spoonacularScore),
                $data->recipes[0]->id);
        }
        // Access the properties of the object
    }

    public function getRecipeById($id)
    {
        $urlID = "https://api.spoonacular.com/recipes/".$id."/information?includeNutrition=false&apiKey=".$this->apiKey;
        $response = file_get_contents($urlID);
        $data = json_decode($response);

        if ($data === null || !isset($data->title)) {
            // Handle the case where the API response is not what you expected
            return ['No title available', 'style/images/WebTech Mascot.jpg', 'Unknown', 'No rating', null];
        }

        // Access properties directly since the response is a single recipe object
        $title = $data->title ?? 'No title available';
        $image = $data->image ?? 'style/images/WebTech Mascot.jpg';
        $readyInMinutes = $data->readyInMinutes ?? 'Unknown';
        $spoonacularScore = isset($data->spoonacularScore) ? intval($data->spoonacularScore) : 0;
        $recipeId = $data->id ?? null;

        return [$title, $image, $readyInMinutes, $spoonacularScore, $recipeId];
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

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
//        $this->apiKey =   'c032d39ece4346bdb75d5e9ac3d6b903';
//        $this->apiKey = 'a97f080d485740608c87a17ef0957691';
//       $this->apiKey = 'face680489cd4b5fbbb1faca74e6ca22';
    }

//    public function getRandomRecipe($filters) {
//
//        $diet = [];
//        $intolerances = [];
//
//        if (!empty($filters['vegetarian'])) {
//            $diet[] = 'vegetarian';
//        }
//        if (!empty($filters['vegan'])) {
//            $diet[] = 'vegan';
//        }
//        if (!empty($filters['gluten-free'])) {
//            $intolerances = "true";
//        }
//        if (!empty($filters['dairy-free'])) {
//            $intolerances = "true";
//        }
//
//        $dietString = implode(',', $diet);
//        $intolerancesString = implode(',',$intolerances);
//        $url = "https://api.spoonacular.com/recipes/complexSearch?number=1&sort=random&diet=".$dietString."&intolerances=".$intolerancesString."&apiKey={$this->apiKey}";
//
////        echo $url;
//
//        $response = file_get_contents($url);
//
//        // Decode the JSON response into a stdClass object
//        $data = json_decode($response);
//
//        $id = $data->recipe[0]->id;
//
//        $urlID = "https://api.spoonacular.com/recipes/".$id."/information?includeNutrition=false&apiKey=".$this->apiKey;
//        $response = file_get_contents($urlID);
//        $data2 = json_decode($response);
//
//
//
//        if(!isset($data2->recipes[0]->image)){ //overwrite image with default
//            $image = $data2->recipes[0]->image ?? 'style/images/WebTech Mascot.jpg';
//            return array($data2->recipes[0]->title, $image, $data2->recipes[0]->readyInMinutes,
//                intval($data2->recipes[0]->spoonacularScore), $data2->recipes[0]->id);
//        }
//        else{
//            return array($data2->recipes[0]->title, $data2->recipes[0]->image,
//                $data2->recipes[0]->readyInMinutes, intval($data2->recipes[0]->spoonacularScore),
//                $data2->recipes[0]->id);
//        }
//        // Access the properties of the object
//    }

    public function getRandomRecipe($filters) {
        $diet = [];
        $intolerances = [];

        if (!empty($filters['vegetarian'])) {
            $diet[] = 'vegetarian';
        }
        if (!empty($filters['vegan'])) {
            $diet[] = 'vegan';
        }
        if (!empty($filters['gluten-free'])) {
            $intolerances[] = 'glutenFree';
        }
        if (!empty($filters['dairy-free'])) {
            $intolerances[] = 'dairyFree';
        }

        $dietString = implode(',', $diet);
        $intolerancesString = implode(',', $intolerances);
        $url = "https://api.spoonacular.com/recipes/complexSearch?number=1&sort=random&diet=" . $dietString . "&intolerances=" . $intolerancesString . "&apiKey={$this->apiKey}";

        //have to use complex search to apply filters and still be random
        //then have to use id search to get more info as its not supplied by complex search

        $response = file_get_contents($url);

        $data = json_decode($response);


        if (isset($data->results[0])) {
            $id = $data->results[0]->id;

            $urlID = "https://api.spoonacular.com/recipes/" . $id . "/information?includeNutrition=false&apiKey=" . $this->apiKey;
            $response = file_get_contents($urlID);
            $data2 = json_decode($response);

            $image = $data2->image ?? 'style/images/WebTech Mascot.jpg';
            return [
                'title' => $data2->title ?? 'No title available',
                'image' => $image,
                'readyInMinutes' => $data2->readyInMinutes ?? 'Unknown',
                'spoonacularScore' => isset($data2->spoonacularScore) ? intval($data2->spoonacularScore) : 0,
                'id' => $data2->id ?? null
            ];
        } else {
            // Handle the case where no recipes are found
            return [
                'title' => 'No title available',
                'image' => 'default_image.png',
                'readyInMinutes' => 'Unknown',
                'spoonacularScore' => 'No rating',
                'id' => null
            ];
        }
    }

    public function getRecipeById($id)
    {
        $urlID = "https://api.spoonacular.com/recipes/".$id."/information?includeNutrition=false&apiKey=".$this->apiKey;
        $response = file_get_contents($urlID);
        $data = json_decode($response);

        if ($data === null || !isset($data->title)) {
            return [
                'title' => 'No title available',
                'image' => 'default_image.png',
                'readyInMinutes' => 'Unknown',
                'spoonacularScore' => 'No rating',
                'id' => null
            ];
        }

        return [
            'recipeName' => $data->title ?? 'No title available',
            'picture' => $data->image ?? 'default_image.png',
            'time' => $data->readyInMinutes ?? 'Unknown',
            'spoonacularScore' => isset($data->spoonacularScore) ? intval($data->spoonacularScore) : 0,
            'id' => $data->id ?? null,
            'servings' => $data->servings ?? null,
            'ingredients' => '',
            'recipeDescription' => $data->summary ?? null,

        ];
    }


    public function searchRecipesByName(string $search){

        $url = "https://api.spoonacular.com/recipes/complexSearch?number=1&sort=random&query=".$search."&apiKey={$this->apiKey}";

        //have to use complex search to apply filters and still be random
        //then have to use id search to get more info as its not supplied by complex search

        $response = file_get_contents($url);

        $data = json_decode($response);


        if (isset($data->results[0])) {
            $id = $data->results[0]->id;

            $urlID = "https://api.spoonacular.com/recipes/" . $id . "/information?includeNutrition=false&apiKey=" . $this->apiKey;
            $response = file_get_contents($urlID);
            $data2 = json_decode($response);

            $image = $data2->image ?? 'style/images/WebTech Mascot.jpg';
            return [
                'title' => $data2->title ?? 'No title available',
                'image' => $image,
                'readyInMinutes' => $data2->readyInMinutes ?? 'Unknown',
                'spoonacularScore' => isset($data2->spoonacularScore) ? intval($data2->spoonacularScore) : 0,
                'id' => $data2->id ?? null
            ];
        } else {
            // Handle the case where no recipes are found
            return [
                'title' => 'No title available',
                'image' => 'default_image.png',
                'readyInMinutes' => 'Unknown',
                'spoonacularScore' => 'No rating',
                'id' => null
            ];
        }


    }

    public function getRecipeByIdFordisplay($id){

        $urlID = "https://api.spoonacular.com/recipes/".$id."/information?includeNutrition=true&apiKey=".$this->apiKey;
        $response = file_get_contents($urlID);
        $data = json_decode($response);

        if ($data === null || !isset($data->title)) {
            return [
                'title' => 'No title available',
                'image' => 'default_image.png',
                'readyInMinutes' => 'Unknown',
                'spoonacularScore' => 'No rating',
                'id' => null
            ];
        }

        return $data;

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

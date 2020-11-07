<?php


namespace App\ApiClient;


use Symfony\Component\HttpClient\HttpClient;

class SpoonacularApiClient
{

    private const API_KEY = 'dc29937fa4504094ab6a7a84b28745bf';
    private $client;

    public function __construct()
    {
        $this->client = HttpClient::create();
    }

    public function getReceipesByIngredients($ingredients)
    {
        $response = $this->client->request(
            'GET',
            'https://api.spoonacular.com/recipes/findByIngredients',
            [
                'headers' => [
                    'Accept' => 'application/json',
                ],
                'query' => [
                    'apiKey' => self::API_KEY,
                    'ingredients' => $ingredients
                ]
            ]
        );
        return $response->toArray();
    }

    public function getReceipeInformation($receipeId)
    {
        $url = 'https://api.spoonacular.com/recipes/' . $receipeId . '/information';
        $response = $this->client->request(
            'GET',
            $url,
            [
                'headers' => [
                    'Accept' => 'application/json',
                ],
                'query' => [
                    'apiKey' => self::API_KEY
                ]
            ]
        );
        return $response->toArray();
    }

    public function convertAmounts($ingredientName, $sourceAmount, $sourceUnit, $targetUnit)
    {
        $response = $this->client->request(
            'GET',
            'https://api.spoonacular.com/recipes/convert',
            [
                'headers' => [
                    'Accept' => 'application/json',
                ],
                'query' => [
                    'apiKey' => self::API_KEY,
                    'ingredientName' => $ingredientName,
                    'sourceAmount' => $sourceAmount,
                    'sourceUnit' => $sourceUnit,
                    'targetUnit' => $targetUnit,
                ]
            ]
        );
        return $response->toArray();
    }

}
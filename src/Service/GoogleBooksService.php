<?php

namespace App\Service;

use Symfony\Component\HttpClient\HttpClient;


class GoogleBooksService
{
    private $apiPath = 'https://www.googleapis.com/books/v1/volumes';
    private $apiKey;

    public function __construct(public string $googleApiKey)
    {
        $this->apiKey = $googleApiKey;
    }

    public function searchBooks(string $query): array
    {

        $httpClient = HttpClient::create();

        $response = $httpClient->request('GET', $this->apiPath, [
            'query' => [
                'title' => $query,
                'key' => $this->apiKey,
            ],
        ]);

        $statusCode = $response->getStatusCode();

        if ($statusCode === 200) {
            $content = $response->toArray();
            // Filtre les livres qui ont une clé imageLinks
            $filteredBooks = array_filter($content['items'], function ($book) {
                return isset($book['volumeInfo']['imageLinks']['thumbnail']);
            });

            return $filteredBooks;
        } else {
            return [];
        }
    }
}

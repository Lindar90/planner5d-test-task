<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class WebPageFetcher
{
    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function fetchContent(string $url): string
    {
        $response = $this->client->request('GET', $url);

        return $response->getContent();
    }
}

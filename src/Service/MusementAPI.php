<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\City;
use RuntimeException;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class MusementAPI
{
    private string $url;

    private HttpClientInterface $client;

    public function __construct(string $url)
    {
        $this->url = $url;

        $this->client = HttpClient::create();
    }

    private function checkResponse(ResponseInterface $response, string $url): void
    {
        $statusCode = $response->getStatusCode();
        if (200 != $statusCode) {
            throw new RuntimeException(sprintf('WebService has returned an invalid HTTP Status Code (%d) at: %s', $statusCode, $url));
        }
        $contentType = $response->getHeaders()['content-type'][0];
        if ('application/json' != $contentType) {
            throw new RuntimeException(sprintf('WebService has returned an invalid Content-Type (%s) at: %s', $contentType, $url));
        }
    }

    /**
     * @param array<string, mixed> $data
     */
    private function buildCityFromRawData(array $data): City
    {
        return new City((int) $data['id'], (string) $data['name'], (float) $data['latitude'], (float) $data['longitude']);
    }

    private function buildUrlforGetCity(int $id): string
    {
        return sprintf('%s/cities/%d', $this->url, $id);
    }

    public function getCity(int $id): City
    {
        $url = $this->buildUrlforGetCity($id);
        $response = $this->client->request('GET', $url);
        $this->checkResponse($response, $url);
        /** @var array<string, mixed> $data */
        $data = $response->toArray();

        return $this->buildCityFromRawData($data);
    }

    private function buildUrlforGetCities(): string
    {
        return sprintf('%s/cities', $this->url);
    }

    /**
     * @return array<int, City>
     */
    public function getCities(): array
    {
        $url = $this->buildUrlforGetCities();
        $response = $this->client->request('GET', $url);
        $this->checkResponse($response, $url);
        $rows = $response->toArray();
        $cities = [];
        /** @var array<string,mixed> $data */
        foreach ($rows as $data) {
            $cities[] = $this->buildCityFromRawData($data);
        }

        return $cities;
    }
}

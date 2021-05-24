<?php

declare(strict_types=1);

namespace App\Service;

use RuntimeException;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class WeatherAPI
{
    private const API_URL_PRODUCTION = 'http://api.weatherapi.com/v1';
    private const FORECAST_LASTDAYS = 2;

    private string $url = self::API_URL_PRODUCTION;
    private string $secret;

    private HttpClientInterface $client;

    public function __construct(string $secret)
    {
        $this->client = HttpClient::create();
        $this->secret = $secret;
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

    private function buildUrlforGetForecast(float $latitude, float $longitude): string
    {
        return sprintf('%s/forecast.json?key=%s&q=%f,%f&days=%u', $this->url, $this->secret, $latitude, $longitude, self::FORECAST_LASTDAYS);
    }

    /**
     * @return array<int, string>
     */
    public function getForecastForLastDays(float $latitude, float $longitude): array
    {
        $url = $this->buildUrlforGetForecast($latitude, $longitude);
        $response = $this->client->request('GET', $url);
        $this->checkResponse($response, $url);
        /** @var array<array-key,array<string,array>> */
        $data = $response->toArray();
        if (!((array_key_exists('forecast', $data)) && (array_key_exists('forecastday', $data['forecast'])) && is_array($data['forecast']['forecastday']))) {
            throw new RuntimeException(sprintf('WebService has returned an invalid response at: %s', $url));
        }
        $forecastday = $data['forecast']['forecastday'];
        /** @var array<int,string> */
        $forecasts = [];
        /** @var array<string,array<string,array<string,string>>> $item */
        foreach ($forecastday as $item) {
            $condition = $item['day']['condition']['text'];
            $forecasts[] = $condition;
        }

        return $forecasts;
    }
}

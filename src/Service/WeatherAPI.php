<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Forecast;
use RuntimeException;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class WeatherAPI
{
    private const FORECAST_LASTDAYS = 2;

    private string $url;
    private string $secret;

    private HttpClientInterface $client;

    public function __construct(string $url, string $secret)
    {
        $this->url = $url;
        $this->secret = $secret;

        $this->client = HttpClient::create();
    }

    private function checkResponse(ResponseInterface $response, string $url): void
    {
        $statusCode = $response->getStatusCode();
        if (200 != $statusCode) {
            $message = sprintf('WebService has returned an invalid HTTP Status Code (%d) at: %s', $statusCode, $url);
            throw new RuntimeException($message);
        }
        $contentType = $response->getHeaders()['content-type'][0];
        if ('application/json' != $contentType) {
            $message = sprintf('WebService has returned an invalid Content-Type (%s) at: %s', $contentType, $url);
            throw new RuntimeException($message);
        }
    }

    private function buildUrlforGetForecast(float $latitude, float $longitude): string
    {
        return sprintf(
            '%s/forecast.json?key=%s&q=%f,%f&days=%u',
            $this->url,
            $this->secret,
            $latitude,
            $longitude,
            self::FORECAST_LASTDAYS
        );
    }

    /**
     * @return array<int, Forecast>
     */
    public function getForecastForLastDays(float $latitude, float $longitude): array
    {
        $url = $this->buildUrlforGetForecast($latitude, $longitude);
        $response = $this->client->request('GET', $url);
        $this->checkResponse($response, $url);
        /** @var array<array-key,array<string,array>> */
        $data = $response->toArray();
        if (
            !(
                (array_key_exists('forecast', $data)) &&
                (array_key_exists('forecastday', $data['forecast'])) &&
                (is_array($data['forecast']['forecastday'])))
        ) {
            $message = sprintf('WebService has returned an invalid response at: %s', $url);
            throw new RuntimeException($message);
        }
        $forecastday = $data['forecast']['forecastday'];
        /** @var array<int,Forecast> */
        $forecasts = [];
        /** @var array<string,array<string,array<string,string>>> $item */
        foreach ($forecastday as $item) {
            $condition = $item['day']['condition']['text'];
            $forecasts[] = new Forecast($condition);
        }

        return $forecasts;
    }
}

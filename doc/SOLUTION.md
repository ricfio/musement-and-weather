# Backend PHP tech homework: A possible solution

## Step 1 | Development

### Introduction

The proposed solution is built with the **Symfony** framework and uses **PHPUnit** as testing framework.

The environment also includes the following tools:

- PHP Coding Standards Fixer
  - **php-cs-fixer**
- PHP Static Analysis Tool
  - **phpstan**
  - **psalm**

I also added a specific Makefile to quickly execute the more frequent commands, for more details:

```bash
make
```  

### Musement's API

I have implemented a service class (and its unit test) to access to Musement's Remote REST API:

- `src/Service/MusementAPI.php`
- `tests/Unit/Service/MusementAPITest.php`

The MusementAPI service has the following main methods:

```php
    /**
     * @return array<array-key, mixed>
     */
    public function getCity(int $id): array

    /**
     * @return array<array-key, mixed>
     */
    public function getCities(): array
```

In the Musement's OpenAPI specifics (saved in the local repository: [`swagger_3.5.0.json`](./api.musement.com/swagger_3.5.0.json) are indicated these servers:

- Sandbox Server API URL: [https://sandbox.musement.com/api/v3](https://sandbox.musement.com/api/v3)
- Production Server API URL: [https://api.musement.com/api/v3](https://api.musement.com/api/v3)

For the development I use the Sandobox Server API URL, but during my tests I have observed that there was frequently problems with host resolution for the Sandbox Server Hostname, so I have solved this issue manually appending some Sandbox Hosts specific IP addresses in the `/etc/hosts` file, as follow:  

`cat /etc/hosts`

```console
... 
13.226.169.58   sandbox.musement.com
52.85.14.127    sandbox.musement.com
52.85.14.11     sandbox.musement.com
```

### Weather's API

I have implemented a service class (and its unit test) to access to Weather's Remote REST API:

- `src/Service/WeatherAPI.php`
- `tests/Unit/Service/WeatherAPITest.php`

The Weather's API URL used for this implementation is:

[http://api.weatherapi.com/v1/forecast.json?key={api-key}&q={latitude},{longitude}&days=2]

- {api-key}: The specific API key of the account forwarding requests
- {latitude}: Latitude of the location for the forecast
- {longitude}: Longitude of the location for the forecast

The WeatherAPI service has the following main methods:

```php
    /**
     * @return array<int, string>
     */
    public function getForecastForLastDays(float $latitude, float $longitude): array
```

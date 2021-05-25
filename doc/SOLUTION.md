# Backend PHP tech homework: A possible solution

## Step 1 | Development

### Introduction

The proposed solution is built with the **Symfony** framework and uses **PHPUnit** as testing framework.

The environment also includes the following tools:

- PHP Coding Standard Fixer
  - **php-cs-fixer**
- PHP Static Analysis Tool
  - **psalm**
  - **phpstan**
  
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

The permanent solution for this issue was adding the "extra_hosts" section in the `docker-compose.yml`:  

```yaml
    extra_hosts:
      - "sandbox.musement.com:13.226.169.58"
      - "sandbox.musement.com:52.85.14.127"
      - "sandbox.musement.com:52.85.14.11"
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

#### Weather's API secret

The API key is sensitive information, so it has been encrypted using the Symfony Secret System.  

```bash
php bin/console secrets:generate-keys
php bin/console secrets:generate-keys --env=prod
```

```bash
php bin/console secrets:set WEATHER_API_KEY
php bin/console secrets:set WEATHER_API_KEY --env=prod
php bin/console secrets:list --reveal
```

So, I have appended the Weather API to the service configuration (`config/services.yaml`) with an alias for testing:

```yaml
    App\Service\WeatherAPI:
        arguments: 
            $secret: '%env(WEATHER_API_KEY)%'
    
    service.weather_api:
        alias: App\Service\WeatherAPI
        public: true
```

and I have added the secret parameter in the Weather API service constructor:

```php
    public function __construct(string $secret)
    {
        $this->client = HttpClient::create();
        $this->secret = $secret;
    }
```

Subsequently, I also implemented a specific Application Test in addition to the previously implemented Unit Test:

- `tests/Application/Service/WeatherAPITest.php`

and I have added the enviornment variable WEATHER_API_KEY in the `.env.test` file for testing:  

```console
WEATHER_API_KEY="xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx"
```

### Console Application

The problem requires the application to print to STDOUT, so I have to write a console application with a command.

I have implemented the following command (and its Application Test) and added its constructor arguments in `services.yaml` file:

- `src/Command/PrintCityForecastCommand.php`
- `tests/Application/PrintCityForecastCommandTest.php`

The test verifies that the output has the expected number of lines and the formatting.

You can run the application (first, add the execution rights if need):

```bash
chmod +x console.php
```

```bash
./console.php print:city-forecast
```

```console
Processed city Amsterdam | Moderate rain - Patchy rain possible
Processed city Paris | Moderate rain - Patchy rain possible
Processed city Rome | Partly cloudy - Partly cloudy
Processed city Milan | Heavy rain - Patchy rain possible
Processed city Barcelona | Patchy rain possible - Cloudy
... 
Processed city Singapore | Patchy rain possible - Patchy rain possible
Processed city Stockholm | Overcast - Patchy rain possible
Processed city Malmö | Partly cloudy - Heavy rain
Processed city Gothenburg | Partly cloudy - Moderate rain
```

## Step 2 | API design (no code required)

### Set the forecast for a specific city on a specific day

The following endpoint permits to save a textual forecast for a specific city on a specific day.  
It will create a new forecast entry for the city (if no forecast exists for that day) or replace the previous forecast.  

The request URL must have the "City Id" of an existing city and a not past day as the "Forecast Day".  
The request Data must include the "Weather conditions" in the following json format: `{"text": "..."}`.  

|                 |            |            |                                              |
|-----------------|------------|------------|----------------------------------------------|
| **Endpoint**    | `PUT /api/v3/cities/{cityId}/forecasts/{day}`                          |
| **Parameters**  | `{cityId}` | `int`      | City Id                                      |
|                 | `{day}`    | `string`   | Forecast Day (format: YYYY-MM-DD)            |
| **Request**     |                                                                        |

```json
{
    "text": ""
}
```

Examples of possible "Weather conditions" are: "Partly cloudy", "Heavy rain".  

| **Responses** |                                           |
|:-------------:|-------------------------------------------|
| 200           | Returned when successful                  |
| 400           | Returned when data payload is incorrect   |
| 403           | Returned when operation is not permitted  |
| 404           | Returned when resource is not found       |
| 503           | Returned when the service is unavailable  |

The response code will be 200 if the forecast was successfully registered.  

In all other cases the forecast will be discarded and not registered, here some examples of errors:  

- 400: invalid day parameter format (use this format: YYYY-MM-DD)
- 400: invalid weather conditions (use this format: {"text": ""})
- 403: invalid day parameter value (must not be a past date)
- 404: city not found

### Read the forecast for a specific city on a specific day

The following endpoint permits to get the current textual forecast for a specific city on a specific day.  
It will return the current weather conditions for the city (if the forecast exists for that day).  

The request URL must have the "City Id" of an existing city and a not past day as the "Forecast Day".  
The "Forecast Day" can be expressed as the date of the required day in the format `{YYYY-MM-DD}` or one of the following alias:  

- today
- tomorrow

|                 |            |            |                                              |
|-----------------|------------|------------|----------------------------------------------|
| **Endpoint**    | `GET /api/v3/cities/{cityId}/forecasts/{day}`                          |
| **Parameters**  | `{cityId}` | `int`      | City Id                                      |
|                 | `{day}`    | `string`   | Forecast Day ({YYYY-MM-DD}, today, tomorrow) |

| **Responses** |                                           |
|:-------------:|-------------------------------------------|
| 200           | Returned when successful                  |
| 400           | Returned when data payload is incorrect   |
| 403           | Returned when operation is not permitted  |
| 404           | Returned when resource is not found       |
| 503           | Returned when the service is unavailable  |

The response code will be 200 if the forecast was successfully returned in the response data.

The responsa data will be an encoded json string similar to the following examples:  

```json
{
    "text": "Moderate rain"
}
```

```json
{
    "text": "Partly cloudy"
}
```

In all other cases will be return only the response code corresponding to the error and no response data:  

- 400: invalid day parameter format ({YYYY-MM-DD}, today, tomorrow)
- 403: invalid day parameter value (must not be a past date)
- 404: city not found

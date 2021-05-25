# Musement and Weather

This is a technical exercise for Backend PHP developers.  

You can find the details about the problem to solve in the [PROBLEM.md](./doc/PROBLEM.md) file.  
The possibile solution proposed by **Riccardo Fiorenza** in this repository was explained in the [SOLUTION.md](./doc/SOLUTION.md) file.

## Application

The software consists in a Console Application implemented using the [Symfony](https://symfony.com/) framework.  

The application can be run with the following command:  

```bash
./console.php print:city-forecast
```

```console
Processed city Amsterdam | Moderate rain - Patchy rain possible
Processed city Paris | Moderate rain - Patchy rain possible
Processed city Rome | Partly cloudy - Partly cloudy
Processed city Milan | Heavy rain - Patchy rain possible
... 
Processed city Gothenburg | Partly cloudy - Moderate rain
```

## Makefile

The project also includes a [`Makefile`](Makefile) as shortcut for the most frequent operations.

```bash
make
```

```console
usage: make [TARGET]

  all          All checks
  cs           Coding Standard (php-cs-fixer)
  sa           Static Analysis (psalm, phpstan)
  run          Application run
  test         Tests execution
```

## Tests execution

The automatic tests are implemented using [PHPUnit](https://phpunit.de/) and can be executed with this make command:  

```bash
make test
```

```console
PHPUnit 9.5.4 by Sebastian Bergmann and contributors.

City (App\Tests\Unit\Entity\City)
 ✔ City instance
 ✔ City id
 ✔ City id must be greater than zero
 ✔ City name
 ✔ City name must have a value
 ✔ City latitude
 ✔ City longitude

Forecast (App\Tests\Unit\Entity\Forecast)
 ✔ City instance
 ✔ Forecast text
 ✔ Forecast text must have a value

Musement API (App\Tests\Unit\Service\MusementAPI)
 ✔ Get city has found milan with right latitude and longitude
 ✔ Get cities has found many cities including rome with right latitude and longitude

Weather API (App\Tests\Unit\Service\WeatherAPI)
 ✔ Get forecast returns last two days

Musement API (App\Tests\Application\Service\MusementAPI)
 ✔ Get city has found milan with right latitude and longitude
 ✔ Get cities has found many cities including rome with right latitude and longitude

Weather API (App\Tests\Application\Service\WeatherAPI)
 ✔ Get forecast returns last two days

Print City Forecast Command (App\Tests\Application\Command\PrintCityForecastCommand)
 ✔ Command has printed 100 rows with expected formattation

Time: 00:19.124, Memory: 24.00 MB
```

## Continuos Integration (CI)

[`.github/workflows/ci.yml`](.github/workflows/ci.yml)

The project includes a CI workflow that performs several checks:

- Coding Standard
- Static Analysis
- Automatic Tests

## Installation

Build and Start the containers:

```bash
docker-compose up --build -d
```

Open the application shell:

```bash
docker-compose exec php-fpm bash
```

The application container shell uses a special custom prompt, similar to the following:

```console
🐳 [b8d647a60e92 app]#
```

Now, from inside the container you can execute all your commands.

Checking the versions of the installed software:

```bash
git --version
php -v
composer -V
symfony -V
make -v
```

If everything has worked correctly, you should see the Welcome Symfony Page opening the following url in your browser:

- [http://localhost/](http://localhost/)

**NOTE**  
In case of conflict problems with your host you can solve them by changing the ports in the `docker-compose.yml`.  

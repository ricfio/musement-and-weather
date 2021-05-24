# Musement and Weather

This is a technical exercise for Backend PHP developers.  
You can find the details about the problem to solve in the [PROBLEM.md](./doc/PROBLEM.md) file.  

The possibile solution proposed by **Riccardo Fiorenza** in this repository was explained in the [SOLUTION.md](./doc/SOLUTION.md) file.

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

## Makefile

```bash
make
```

```console
 all                    All checks
 analyze                Static analysis
 analyze-psalm          Static analysis (only with psalm)
 analyze-phpstan        Static analysis (only with phpstan)
 fix                    Code fixing
 run                    Application run
 test                   Tests execution
```

## Run Application

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

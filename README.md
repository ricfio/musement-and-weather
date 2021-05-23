# Musement and Weather

This is a technical exercise for Backend PHP developers.  
You can find the details about the problem to solve in the [PROBLEM.md](./doc/PROBLEM.md) file.  

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

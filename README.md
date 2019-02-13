# Docker-Jenkins-Traefik-Nginx-MySQL-PHP-Lumen Bootstrap

Scaffolding project to bootstrap for new PHP / Lumen microservice with MySQL running on Nginx with Traefik, Jenkinsfile for CI and Docker / testing scripts.

# Install

- Copy the files from this repository into your new repository and move to that directory.
- Request DevOps to integrate the repository with Jenkins to enable the build pipeline.
- Copy the `.env.example` file to `.env` and configure for your environment.
- Configure Docker as per the instructions below.
- Configure Services required as per the instructions below.
- Configure the `src/composer.json`, `src/routes/web.php`, `src/bootstrap/app.php` files as required by the application.****

# Docker Config

The service uses docker-compose which will host a local containerised environment for the application with Traefik for routing, and run with the build and deployment pipelines.

The `docker-compose.yml` file is used for the default `docker-compose` command and should be configured to work on your local dev environment.

The `docker-compose-test.yml` file is used for the Jenkins environment.

Configure the compose.yml files with the services necessary for the application - nginx, php-fpm and mysql templates are provided.

Unused services should be removed from both `docker-compose.yml` files, the `.env` file and docker directory.

**Service container_name keys in the docker-compose.yml file must be configured to the unique service name to allow for multiple containers to be run at the same time on local development**

**Network aliases must be configured to use unique service names in the docker-compose-test.yml file**

# Nginx Config

Nginx should work out of the box. The only change required is to the `fastcgi_pass` setting in the `/docker/nginx/site.conf` file which should be set to the unique php container name e.g. `myapi_php`. 

This change is only required for local development. This prevents round-robin DNS issues when running multiple services in dev.

# MySQL Config

MySQL host and database settings should be configured in both `docker-compose.yml` config files and in the `.env` file.

# Jenkinsfile Config

The only thing that should need configuring in the Jenkinsfile are the ECR environment keys.

# Running Docker

Run `docker-compose up` from the root directory and this will build the application containers, start the webserver and PHP, install composer and run any migrations.

You can then simply view `http://localhost:8001/api/v1/service_name` in a browser to view.

# Running Commands

If you need to run commands on the container you can open a shell with `docker exec -it php sh` to access php directly.

Alternatively you can run commands directly e.g. `docker exec -it php php artisan make:migration create_table`

# Composer Install

`docker exec -it php composer install`

# Artisan Migrate

`docker exec -it php php artisan migrate`

# Local PHP Server

As a shortcut a local copy of PHP can be used to run a local webserver, run `php -S localhost:8000 -t public` from the `src` dir and open `http://localhost:8000` in a browser.

# Running Multiple Services In Local Dev

To assist with running multiple services in a local development environment the `andyburton/traefik-environment` repository has been configured with Traefik to handle URL routing.

To install this clone the dev-environment, run `docker network create microservices` and then `docker-compose up -d`.

This will start Traefik and all subsequent services will register their routes with them and can be viewed on `http://localhost:8001` e.g. `http://localhost:8001/api/v1/service`

Further information can be found in the `traefik-environment` repository README.
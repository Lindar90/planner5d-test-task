_Hello dear Planner 5D team,_

_Please read the following doc thorougly, I tried to provide as much information as possible bellow._

_Not everything was implemented smoothly, but I hope you will show some understanding as the task was done under great uncertainty._

_Also I hope you enjoy my work and wish you a good day!_

# Improvements/TODO

1. **MAJOR BUG: floor plan is rendered incorrectly. For most of the projects rooms are rendered incorrectly**

- Due to the limited information how to get the project room information, I wasn't able to draw the floor plan without the issues.
- I found that the project data is returned by this API endpoint: `https://planner5d.com/api/project/{$projectId}`.
- I was able to fetch information about room walls coordinates, but without proper access the API documentation I didn't manage to drow rooms at the correct positions.

2. script doesn't check existing records - it works in append mode only.
3. script is failing to import one project - should be fixed.
4. logging improvements. monolog should be used. create a file with logs per script run (with timestamp in the name).
5. unit tests code coverage

# Prerequisites

Docker should be installed and running on your operating system.

# Installation

```bash
$ docker-compose up -d --build

$ docker-compose exec php composer install
$ docker-compose exec php npm install
$ docker-compose exec php npm run dev

$ docker-compose exec php bin/console doctrine:database:create --no-interaction
$ docker-compose exec php bin/console doctrine:migrations:migrate --no-interaction

$ docker-compose exec php php -S 0.0.0.0:8000 -t public/
```

It will do the following:

- run PHP container
- install PHP, JS dependencies
- create SQLite database
- run migration

The website will be available by this URL: `http://localhost:8000/`

# Available routes

1. `http://localhost:8000/` - renders pginated list of projects
2. `http://localhost:8000/projects/${id}` - renders project details
3. `http://localhost:8000/projects/import` - calls import script, accepts `pages_limit` query param.

# Import script

```bash
$ docker-compose exec php php bin/console planner:import-projects

$ docker-compose exec php php bin/console planner:import-projects --numberOfPages 2
```

# Unit tests

```bash
$ docker-compose exec php php bin/phpunit
```

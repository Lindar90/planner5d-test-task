#! /bin/bash

docker-compose build --no-cache
docker-compose up -d

docker-compose exec php composer install
docker-compose exec php npm install
docker-compose exec php npm run dev

docker-compose exec php bin/console doctrine:database:create --no-interaction
docker-compose exec php bin/console doctrine:migrations:migrate --no-interaction

docker-compose exec php php -S 0.0.0.0:8000 -t public/

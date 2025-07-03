.PHONY: up down build composer-install

up:
	docker compose up -d

down:
	docker compose down

build:
	docker compose up -d --build

composer-install:
	docker compose exec php composer install

composer-update:
	docker compose exec php composer update

test:
	docker compose exec php vendor/bin/phpunit
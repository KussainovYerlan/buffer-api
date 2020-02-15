.PHONY: up down install bash cacl

up:
	docker-compose up -d

down:
	docker-compose down

install:
	docker-compose build \
	&& docker-compose up -d \
	&& docker-compose exec -u www-data php composer install

bash:
	docker-compose exec -u www-data php bash

cacl:
	docker-compose exec -u www-data php php -d memory_limit=-1 bin/console ca:cl
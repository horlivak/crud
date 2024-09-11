#!make

ifeq (, $(shell which docker-compose))
command := docker compose
else
command := docker-compose
endif


build:
	${command} build

composer-install:
	${command} run --rm php composer install --ignore-platform-reqs

load:
	make up
	${command} run --rm php bin/console d:m:migrate --no-interaction
	${command} run --rm php bin/console d:f:load -n

up:
	${command} up -d --remove-orphans

down:
	${command} kill
	${command} down

restart:
	make down
	make up

bash:
	${command} exec php /bin/bash
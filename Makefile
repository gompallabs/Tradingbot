APP_NAME = tmpl_manager_7
BEHAT_BIN = vendor/bin/behat
RUN_IN_CONTAINER = docker compose exec ${APP_NAME}

PARENT_DIR := $(shell dirname $(CURDIR))
DOCKER_DEV := docker compose -f docker-compose.yml -f docker-compose.dev.yml

# docker
app:
	docker exec -ti ${APP_NAME} sh

build:
	docker compose up --build --remove-orphans -d

up:
	docker compose up -d

up-dev:
	${DOCKER_DEV} up -d

down:
	${DOCKER_DEV} down

help:
	@echo "Usage: make [target]"
	@echo "Targets:"
	@echo "  build   Build the application"
	@echo "  up     Run the application"
	@echo "  help    Display this help message"


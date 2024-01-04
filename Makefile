PARENT_DIR := $(shell dirname $(CURDIR))
DOCKER_DEV := docker compose -f docker-compose.yml -f docker-compose.dev.yml

# docker
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


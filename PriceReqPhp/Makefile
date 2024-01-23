BEHAT_BIN = vendor/bin/behat
PHPUNIT_BIN = vendor/bin/phpunit

APP_NAME = price_back
RUN_IN_CONTAINER = docker compose exec ${APP_NAME}

PARENT_DIR := $(shell dirname $(CURDIR))
DOCKER_DEV := docker compose -f ${PARENT_DIR}/docker-compose.yml -f ${PARENT_DIR}/docker-compose.dev.yml

# help
help: ## Show this help
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "%-20s %s\n", $$1, $$2}'
	@echo "Usage: make [target]"
		@echo "Targets:"
		@echo "  build	Build the application"
		@echo "  up     Run the application"
		@echo "  help   Display this help message"

# shell
app:
	${RUN_IN_CONTAINER} sh

# database
create_db:
	bin/console doctrine:database:drop -f
	bin/console doctrine:database:create

run_migrations:
	bin/console doctrine:migrations:migrate -n

init:
	mkdir -p $(CURDIR)/var/data
	make create_db
	make run_migrations

# quality
cs-fixer:
	${RUN_IN_CONTAINER} sh -c "./vendor/bin/php-cs-fixer fix src"
	${RUN_IN_CONTAINER} sh -c "./vendor/bin/php-cs-fixer fix tests"

stan:
	${RUN_IN_CONTAINER} sh -c "./vendor/bin/phpstan analyze src --level=5"
	${RUN_IN_CONTAINER} sh -c "./vendor/bin/phpstan analyze tests --level=5"

# tests
behat:
	docker compose exec ${APP_NAME} sh -c $(BEHAT_BIN) --verbose --colors

unit:
	docker compose exec ${APP_NAME} sh -c $(PHPUNIT_BIN)


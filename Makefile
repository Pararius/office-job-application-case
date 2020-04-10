help: ## Show this help message.
	@echo 'usage: make [target] ...'
	@echo
	@echo 'targets:'
	@egrep '^(.+)\:(.+)?\ ##\ (.+)' ${MAKEFILE_LIST} | column -t -c 2 -s ':#'
.PHONY: help

install: ## Installs all dependencies for this service
	docker-compose run --rm php composer install --optimize-autoloader --no-interaction
.PHONY: install

up: ## Starts depended-on containers
	docker-compose up -d firestore pubsub
.PHONY: up

run: up ## Runs the commands
	docker-compose run --rm php bin/console listings:import
	docker-compose run --rm php bin/console listings:process
	docker-compose run --rm php bin/console listings:show
.PHONY: run

test: up ## Runs test suite
	docker-compose run --rm php bin/phpunit
	docker-compose down -v --remove-orphans
.PHONY: test

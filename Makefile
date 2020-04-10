help: ## Show this help message.
	@echo 'usage: make [target] ...'
	@echo
	@echo 'targets:'
	@egrep '^(.+)\:(.+)?\ ##\ (.+)' ${MAKEFILE_LIST} | column -t -c 2 -s ':#'
.PHONY: help

install: ## Installs all dependencies for this service
	docker-compose run --rm php composer install --optimize-autoloader --no-interaction
.PHONY: install

test: ## Runs test suite
	docker-compose run --rm test bin/phpunit
	docker-compose kill test-pubsub test-firestore
.PHONY: test

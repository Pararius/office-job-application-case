help: ## Show this help message.
	@echo 'usage: make [target] ...'
	@echo
	@echo 'targets:'
	@egrep '^(.+)\:(.+)?\ ##\ (.+)' ${MAKEFILE_LIST} | column -t -c 2 -s ':#'
.PHONY: help

install: ## Installs all dependencies for this service
	docker-compose run --rm php composer install --optimize-autoloader --no-interaction
	docker-compose run --rm php bin/console gcloud:setup:firestore
	docker-compose run --rm php bin/console gcloud:setup:pubsub
.PHONY: install

test: ## Runs test suite
	docker-compose up -d test-pubsub
	docker-compose run --rm test bin/phpunit
	docker-compose stop test-pubsub
.PHONY: test

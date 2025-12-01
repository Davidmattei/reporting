#!/usr/bin/make -f

.DEFAULT_GOAL := help
.PHONY: help

help: # Show help for each of the Makefile recipes.
	@echo "Reporting"
	@echo ""
	@echo "Usage: make [target]"
	@echo "Targets:"
	@grep -E '(^\S*:.*?##.*$$)|(^##)' Makefile | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

## —— Server ———————————————————————————————————————————————————————————————————————————————————————————————————————————
start: ## start symfony server
	symfony server:start -d --no-tls
stop: ## stop symfony server
	symfony server:stop
logs: ## logs symfony server
	symfony server:log

## —— Database —————————————————————————————————————————————————————————————————————————————————————————————————————————
db-up: ## update database
	php bin/console doctrine:schema:update --force
db-refresh: ## drop and create database
	php bin/console doctrine:database:drop --force
	php bin/console doctrine:schema:update --force
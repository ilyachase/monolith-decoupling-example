# Executables (local)
DOCKER_COMP = docker-compose

# Docker containers
PHP_CONT_COURIER = $(DOCKER_COMP) exec courier-service
PHP_CONT_CUSTOMER = $(DOCKER_COMP) exec customer-service
PHP_CONT_RESTAURANT = $(DOCKER_COMP) exec restaurant-service

# Executables
PHP_COURIER      = $(PHP_CONT_COURIER) php
PHP_CUSTOMER      = $(PHP_CONT_CUSTOMER) php
PHP_RESTAURANT      = $(PHP_CONT_RESTAURANT) php
COMPOSER_COURIER = $(PHP_CONT_COURIER) composer
COMPOSER_CUSTOMER = $(PHP_CONT_CUSTOMER) composer
COMPOSER_RESTAURANT = $(PHP_CONT_RESTAURANT) composer
SYMFONY_COURIER  = $(PHP_COURIER) bin/console
SYMFONY_CUSTOMER  = $(PHP_CUSTOMER) bin/console
SYMFONY_RESTAURANT  = $(PHP_RESTAURANT) bin/console

# Misc
.DEFAULT_GOAL = help
.PHONY        : help build up start down logs sh composer vendor sf cc

## —— 🎵 🐳 The Symfony Docker Makefile 🐳 🎵 ——————————————————————————————————
help: ## Outputs this help screen
	@grep -E '(^[a-zA-Z0-9\./_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

## —— Docker 🐳 ————————————————————————————————————————————————————————————————
build: ## Builds the Docker images
	@$(DOCKER_COMP) build

up: ## Start the docker hub in detached mode (no logs)
	@$(DOCKER_COMP) up --detach

start: build up ## Build and start the containers

down: ## Stop the docker hub
	@$(DOCKER_COMP) down --remove-orphans

logs: ## Show live logs
	@$(DOCKER_COMP) logs --tail=50 --follow

sh: ## Connect to the PHP FPM container
	@$(PHP_CONT_COURIER) sh

bash_courier: ## Connect to the PHP FPM container
	@$(PHP_CONT_COURIER) bash

bash_customer: ## Connect to the PHP FPM container
	@$(PHP_CONT_CUSTOMER) bash

bash_restaurant: ## Connect to the PHP FPM container
	@$(PHP_CONT_RESTAURANT) bash

composer_courier: ## Run composer, pass the parameter "c=" to run a given command, example: make composer c='req symfony/orm-pack'
	@$(eval c ?=)
	@$(COMPOSER_COURIER) $(c)

composer_customer: ## Run composer, pass the parameter "c=" to run a given command, example: make composer c='req symfony/orm-pack'
	@$(eval c ?=)
	@$(COMPOSER_CUSTOMER) $(c)

composer_restaurant: ## Run composer, pass the parameter "c=" to run a given command, example: make composer c='req symfony/orm-pack'
	@$(eval c ?=)
	@$(COMPOSER_RESTAURANT) $(c)

vendor_courier: ## Install vendors according to the current composer.lock file
vendor_courier: c=install --prefer-dist --no-progress --no-scripts --no-interaction
vendor_courier: composer_courier

vendor_customer: ## Install vendors according to the current composer.lock file
vendor_customer: c=install --prefer-dist --no-progress --no-scripts --no-interaction
vendor_customer: composer_customer

vendor_restaurant: ## Install vendors according to the current composer.lock file
vendor_restaurant: c=install --prefer-dist --no-progress --no-scripts --no-interaction
vendor_restaurant: composer_restaurant

sf_courier: ## List all Symfony commands or pass the parameter "c=" to run a given command, example: make sf c=about
	@$(eval c ?=)
	@$(SYMFONY_COURIER) $(c)

sf_customer: ## List all Symfony commands or pass the parameter "c=" to run a given command, example: make sf c=about
	@$(eval c ?=)
	@$(SYMFONY_CUSTOMER) $(c)

sf_restaurant: ## List all Symfony commands or pass the parameter "c=" to run a given command, example: make sf c=about
	@$(eval c ?=)
	@$(SYMFONY_RESTAURANT) $(c)

db_courier: c=doctrine:database:create --if-not-exists -c courier
db_courier: sf_courier

db_customer: c=doctrine:database:create --if-not-exists -c customer
db_customer: sf_customer

db_restaurant: c=doctrine:database:create --if-not-exists -c restaurant
db_restaurant: sf_restaurant

courier_migration: c=doctrine:migrations:migrate -n --em courier --configuration config/doctrine_migrations_courier.yaml
courier_migration: sf_courier

customer_migration: c=doctrine:migrations:migrate -n --em customer --configuration config/doctrine_migrations_customer.yaml
customer_migration: sf_customer

restaurant_migration: c=doctrine:migrations:migrate -n --em restaurant --configuration config/doctrine_migrations_restaurant.yaml
restaurant_migration: sf_restaurant

fixture: c=doctrine:fixtures:load -n
fixture: sf_restaurant
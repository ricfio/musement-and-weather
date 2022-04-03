.PHONY: help all check clean run test install-deps outdated-deps update-deps cs cs-php-cs-fixer cs-phpmd sa sa-psalm sa-phpstan

help:
	@awk 'BEGIN {FS = ":.*#"; printf "\nUsage:\n  make \033[36m<target>\033[0m\n\n"} /^[a-zA-Z0-9_-]+:.*?#/ { printf "  \033[36m%-27s\033[0m %s\n", $$1, $$2 } /^##@/ { printf "\n\033[1m%s\033[0m\n", substr($$0, 5) } ' $(MAKEFILE_LIST); printf "\n"

all: ## Build all
all: install-deps

check: ## Check application
check: all cs sa test

clean: ## Clean up project
	composer clearcache && rm -f composer.lock; rm -rf vendor/*

run: ## Run application
	./console.php print:city-forecast

test: ## Test application
	./vendor/bin/phpunit --testdox

install-deps:
	composer install

outdated-deps:
	composer outdated

update-deps:
	composer update

cs: cs-php-cs-fixer cs-phpmd

cs-php-cs-fixer:
	./vendor/bin/php-cs-fixer fix

cs-phpmd:
	./vendor/bin/phpmd src,tests ansi cleancode,codesize,controversial,design,naming,unusedcode --exclude src/Kernel.php

sa: sa-psalm sa-phpstan

sa-psalm:
	./vendor/bin/psalm

sa-phpstan:
	./vendor/bin/phpstan

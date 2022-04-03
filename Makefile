.PHONY: help all clean install cs cs-php-cs-fixer cs-phpmd sa sa-psalm sa-phpstan run test

help:
	@awk 'BEGIN {FS = ":.*#"; printf "\nUsage:\n  make \033[36m<target>\033[0m\n\n"} /^[a-zA-Z0-9_-]+:.*?#/ { printf "  \033[36m%-27s\033[0m %s\n", $$1, $$2 } /^##@/ { printf "\n\033[1m%s\033[0m\n", substr($$0, 5) } ' $(MAKEFILE_LIST); printf "\n"

all: ## All checks
all: cs sa test

clean: ## Clean up the project
	composer clearcache && rm -f composer.lock; rm -rf vendor/*

install: ## Install dependencies
	composer install

cs: ## Coding Style (php-cs-fixer, phpmd)
cs: cs-php-cs-fixer cs-phpmd

cs-php-cs-fixer:
	./vendor/bin/php-cs-fixer fix

cs-phpmd:
	./vendor/bin/phpmd src,tests ansi cleancode,codesize,controversial,design,naming,unusedcode --exclude src/Kernel.php

sa: ## Static Analysis (psalm, phpstan)
sa: sa-psalm sa-phpstan

sa-psalm:
	./vendor/bin/psalm

sa-phpstan:
	./vendor/bin/phpstan

run: ## Application run
	./console.php print:city-forecast

test: ## Tests execution (phpunit)
	./vendor/bin/phpunit --testdox

.PHONY: help
help : Makefile
	@echo "usage: make [TARGET]"
	@echo 
	@sed -n 's/^## HELP://p' $<
	@echo 

## HELP:  all          All checks
.PHONY: all
all: cs sa test

## HELP:  cs           Coding Standard (php-cs-fixer)
.PHONY: cs
cs: cs-php-cs-fixer

.PHONY: cs-php-cs-fixer
cs-php-cs-fixer:
	./vendor/bin/php-cs-fixer fix

## HELP:  sa           Static Analysis (psalm, phpstan)
.PHONY: sa
sa: sa-psalm sa-phpstan

.PHONY: sa-psalm
sa-psalm:
	./vendor/bin/psalm

.PHONY: sa-phpstan
sa-phpstan:
	./vendor/bin/phpstan

## HELP:  run          Application run
.PHONY: run
run:
	./console.php print:city-forecast

## HELP:  test         Tests execution (phpunit)
.PHONY: test
test:
	./vendor/bin/phpunit --testdox

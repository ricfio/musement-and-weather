.PHONY: help
help : Makefile
	@sed -n 's/^## HELP://p' $<

## HELP: all                    All checks
.PHONY: all
all: fix analyze test

## HELP: analyze                Static analysis
.PHONY: analyze
analyze: analyze-psalm analyze-phpstan

## HELP: analyze-psalm          Static analysis (only with psalm)
.PHONY: analyze-psalm
analyze-psalm:
	vendor/bin/psalm

## HELP: analyze-phpstan        Static analysis (only with phpstan)
.PHONY: analyze-phpstan
analyze-phpstan:
	vendor/bin/phpstan

## HELP: fix                    Code fixing
.PHONY: fix
fix:
	vendor/bin/php-cs-fixer fix

## HELP: run                    Application run
.PHONY: run
run:
	./console.php print:city-forecast

## HELP: test                   Tests execution
.PHONY: test
test:
	vendor/bin/phpunit --testdox

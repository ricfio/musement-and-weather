.PHONY: help
help : Makefile
	@sed -n 's/^## HELP://p' $<

## HELP: all                    All checks
.PHONY: all
all: cs analyze test

## HELP: cs                     Coding Standard (php-cs-fixer)
.PHONY: cs
cs: cs-php-cs-fixer

.PHONY: cs-php-cs-fixer
cs-php-cs-fixer:
	vendor/bin/php-cs-fixer fix

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

## HELP: run                    Application run
.PHONY: run
run:
	./console.php print:city-forecast

## HELP: test                   Tests execution
.PHONY: test
test:
	vendor/bin/phpunit --testdox

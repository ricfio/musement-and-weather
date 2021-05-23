help : Makefile
	@sed -n 's/^## HELP://p' $<

## HELP: all                    All checks
all: analyze fix test

## HELP: analyze                Static analysis
analyze: analyze-psalm analyze-phpstan

## HELP: analyze-psalm          Static analysis (only with psalm)
analyze-psalm:
	vendor/bin/psalm

## HELP: analyze-phpstan        Static analysis (only with phpstan)
analyze-phpstan:
	vendor/bin/phpstan

## HELP: fix                    Code fixing
fix:
	vendor/bin/php-cs-fixer fix

## HELP: test                   Test execution
test:
	vendor/bin/phpunit --testdox

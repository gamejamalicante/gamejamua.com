.PHONY: cs-fix

cs-fix:
	./vendor/bin/php-cs-fixer fix . --config=sf23 -vvv

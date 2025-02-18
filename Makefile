### ——————————————————————————————————————————————————————————————————
### —— Local Makefile
### ——————————————————————————————————————————————————————————————————

include vendor/badpixxel/php-sdk/make/sdk.mk

start: 	## Execute Functional Test
	symfony serve --no-tls

verify: ## Execute Code Quality Tests
	bash ci/verify.sh

test: 	## Execute Functional Test
	php vendor/bin/phpunit --testdox


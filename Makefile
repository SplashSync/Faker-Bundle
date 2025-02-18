### ——————————————————————————————————————————————————————————————————
### —— Local Makefile
### ——————————————————————————————————————————————————————————————————

include vendor/badpixxel/php-sdk/make/sdk.mk

test: ## Execute Functional Test
	php vendor/bin/phpunit --testdox


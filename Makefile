SHELL := /usr/bin/env bash

.PHONY: test
test: phpunit phpmd phpcs

.PHONY: phpcs
phpcs:
	./vendor/bin/php-cs-fixer fix --dry-run

.PHONY: phpmd
phpmd:
	./vendor/bin/phpmd $$((\
		find * -maxdepth 0 -not -name 'vendor' -not -name 'Tests' -type d && \
		find Tests/ -mindepth 1 -maxdepth 1 -not -name 'Fixtures' && \
		find Tests/Fixtures/ -mindepth 2 -maxdepth 2 -not -name 'var' \
		) | paste --delimiter , --serial) text phpmd.xml

.PHONY: phpunit
phpunit:
	./vendor/bin/phpunit

.PHONY: update-test
update-test: | composer
	rm -rf Tests/Fixtures/TestProject/cache/test/
	./composer install

composer:
	$(if $(shell which composer 2> /dev/null),\
        ln --symbolic $$(which composer) composer,\
		curl --silent --show-error https://getcomposer.org/installer | php -- --install-dir=$$(pwd) --filename=composer)

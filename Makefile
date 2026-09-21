## ACCOUNT

.PHONY: account-init
account-init:
	cd seaghi-account &&\
	composer install

.PHONY: account-start
account-start:
	cd seaghi-account &&\
	symfony server:start --allow-http --no-tls --port=8000

## SHOP

.PHONY: shop-init
shop-init:
	cd seaghi-shop &&\
	composer install &&\
	bin/console doctrine:database:drop --force &&\
	bin/console doctrine:migrations:migrate -n &&\
	cat fixtures.sql | xargs -0 bin/console dbal:run-sql

.PHONY: shop-start
shop-start:
	cd seaghi-shop &&\
	symfony server:start --allow-http --no-tls --port=8001

## BATTLE

.PHONY: battle-init
battle-init:
	cd seaghi-battle &&\
	composer install &&\
	bin/console doctrine:database:drop --force &&\
	bin/console doctrine:migrations:migrate -n

.PHONY: battle-start
battle-start:
	cd seaghi-battle &&\
	symfony server:start --allow-http --no-tls --port=8002

## QA

.PHONY: deptrac
deptrac:
	./seaghi-qa/tools/deptrac/vendor/bin/deptrac analyse --config-file=seaghi-qa/tools/deptrac/deptrac.yaml

.PHONY: phpcs
phpcs:
	cd seaghi-qa/tools/phpcs/ && ./vendor/bin/php-cs-fixer check --diff

.PHONY: phpmd
phpmd:
	./seaghi-qa/tools/phpmd/vendor/bin/phpmd analyze seaghi-account seaghi-battle seaghi-shop --ruleset=seaghi-qa/tools/phpmd/phpmd.xml

.PHONY: phpstan
phpstan:
	./seaghi-qa/tools/phpstan/vendor/bin/phpstan analyse --configuration=seaghi-qa/tools/phpstan/phpstan.neon


.PHONY: phpunit
phpunit:
	./seaghi-qa/tools/phpunit/vendor/bin/phpunit --configuration seaghi-qa/tools/phpunit/phpunit.xml

.PHONY: rector
rector:
	./seaghi-qa/tools/rector/vendor/bin/rector process --dry-run --config=seaghi-qa/tools/rector/rector.php

.PHONY: %-orm-mapping-validation
%-orm-mapping-validation:
	cd seaghi-$* &&\
	bin/console doctrine:schema:validate --skip-sync

.PHONY: qa-vendor-update
qa-vendor-update:
	@echo "======== QA VENDOR UPDATE ========"
	@echo "-------- Update phpcs --------"
	composer update --working-dir=seaghi-qa/tools/phpcs
	@echo "-------- Update phpmd --------"
	composer update --working-dir=seaghi-qa/tools/phpmd
	@echo "-------- Update phpstan --------"
	composer update --working-dir=seaghi-qa/tools/phpstan
	@echo "-------- Update phpunit --------"
	composer update --working-dir=seaghi-qa/tools/phpunit
	@echo "-------- Update rector --------"
	composer update --working-dir=seaghi-qa/tools/rector
	@echo "-------- Update deptrac --------"
	composer update --working-dir=seaghi-qa/tools/deptrac

.PHONY: qa-vendor-install
qa-vendor-install:
	@echo "======== QA VENDOR INSTALL ========"
	@echo "-------- Install phpcs --------"
	composer install --working-dir=seaghi-qa/tools/phpcs
	@echo "-------- Install phpmd --------"
	composer install --working-dir=seaghi-qa/tools/phpmd
	@echo "-------- Install phpstan --------"
	composer install --working-dir=seaghi-qa/tools/phpstan
	@echo "-------- Install phpunit --------"
	composer install --working-dir=seaghi-qa/tools/phpunit
	@echo "-------- Install rector --------"
	composer install --working-dir=seaghi-qa/tools/rector
	@echo "-------- Install deptrac --------"
	composer install --working-dir=seaghi-qa/tools/deptrac

.PHONY: qa-vendor-remove
qa-vendor-remove:
	rm -fr seaghi-qa/tools/phpcs/vendor
	rm -fr seaghi-qa/tools/phpmd/vendor
	rm -fr seaghi-qa/tools/phpstan/vendor
	rm -fr seaghi-qa/tools/phpunit/vendor
	rm -fr seaghi-qa/tools/rector/vendor
	rm -fr seaghi-qa/tools/deptrac/vendor

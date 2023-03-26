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
	bin/console doctrine:database:create &&\
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
	bin/console doctrine:database:create &&\
	bin/console doctrine:migrations:migrate -n

.PHONY: battle-start
battle-start:
	cd seaghi-battle &&\
	symfony server:start --allow-http --no-tls --port=8002

## QA

.PHONY: img-phpcs
img-phpcs:
	$(shell [ -z "$$(docker images -q phpcs)" ] && docker build -f seaghi-qa/dockerfiles/Dockerfile.phpcs -t phpcs .)

.PHONY: %-phpcs
%-phpcs: img-phpcs
	docker run --rm -v $$(pwd)/seaghi-$*:/repo -u $$(id -u ${USER}):$$(id -g ${USER}) phpcs

.PHONY: img-phpcbf
img-phpcbf:
	$(shell [ -z "$$(docker images -q phpcbf)" ] && docker build -f seaghi-qa/dockerfiles/Dockerfile.phpcbf -t phpcbf .)

.PHONY: %-phpcbf
%-phpcbf: img-phpcbf
	docker run --rm -v $$(pwd)/seaghi-$*:/repo -u $$(id -u ${USER}):$$(id -g ${USER}) phpcbf

.PHONY: img-phpmd
img-phpmd:
	$(shell [ -z "$$(docker images -q phpmd)" ] && docker build -f seaghi-qa/dockerfiles/Dockerfile.phpmd -t phpmd .)

.PHONY: %-phpmd
%-phpmd: img-phpmd
	docker run --rm -v $$(pwd)/seaghi-$*:/repo -u $$(id -u ${USER}):$$(id -g ${USER}) phpmd . text phpmd.xml

.PHONY: img-phpstan
img-phpstan:
	$(shell [ -z "$$(docker images -q phpstan)" ] && docker build -f seaghi-qa/dockerfiles/Dockerfile.phpstan -t phpstan .)

.PHONY: %-phpstan
%-phpstan: img-phpstan
	docker run --rm -v $$(pwd)/seaghi-$*:/repo -u $$(id -u ${USER}):$$(id -g ${USER}) phpstan analyse -c phpstan.neon

.PHONY: img-phpunit
img-phpunit:
	$(shell [ -z "$$(docker images -q phpunit)" ] && docker build -f seaghi-qa/dockerfiles/Dockerfile.phpunit -t phpunit .)

.PHONY: %-phpunit
%-phpunit: img-phpunit
	docker run --rm -v $$(pwd)/seaghi-$*:/repo -u $$(id -u ${USER}):$$(id -g ${USER}) phpunit

.PHONY: img-deptrac
img-deptrac:
	$(shell [ -z "$$(docker images -q deptrac)" ] && docker build -f seaghi-qa/dockerfiles/Dockerfile.deptrac -t deptrac .)

.PHONY: %-deptrac
%-deptrac: img-deptrac
	docker run --rm -v $$(pwd)/seaghi-$*:/repo -u $$(id -u ${USER}):$$(id -g ${USER}) deptrac

.PHONY: %-orm-mapping-validation
%-orm-mapping-validation:
	cd seaghi-$* &&\
	bin/console doctrine:schema:validate --skip-sync

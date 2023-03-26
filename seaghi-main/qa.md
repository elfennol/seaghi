# QA

## General Requirement

- Docker

## Deptrac

```shell
make shop-deptrac
make battle-deptrac
```

To get an image, use a command like this:

```sh
# docker build -f ./Dockerfile.deptrac -t deptrac .
docker run --rm -v $(pwd)/seaghi-battle:/repo -u $(id -u ${USER}):$(id -g ${USER})\
 deptrac --formatter=graphviz-image --output=graph.png
```

## PHPCS

```shell
make account-phpcs
make shop-phpcs
make battle-phpcs
```

## PHPCBF

```shell
make account-phpcbf
make shop-phpcbf
make battle-phpcbf
```

## PHPMD

```shell
make account-phpmd
make shop-phpmd
make battle-phpmd
```

## PHPStan

```shell
make account-phpstan
make shop-phpstan
make battle-phpstan
```

## PHPUnit

```shell
make shop-phpunit
make battle-phpunit
```

## Functional tests

See [seaghi-qa/functional-tests/play.http](../seaghi-qa/functional-tests/play.http).

## Doctrine mapping validation

```shell
make shop-orm-mapping-validation
make battle-orm-mapping-validation
```

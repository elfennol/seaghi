# QA

```sh
make deptrac
make phpcs
make phpmd
make phpstan
make phpunit
make rector
```

All:

```sh
make qa
```

## Functional tests

See [seaghi-qa/functional-tests/play.http](../seaghi-qa/functional-tests/play.http).

## Doctrine mapping validation

```shell
make shop-orm-mapping-validation
make battle-orm-mapping-validation
```

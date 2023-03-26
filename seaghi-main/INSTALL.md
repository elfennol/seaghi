# Install

## General Requirement

- PHP 8.2
- Redis

## Account

- Init seaghi-account: `make account-init`
- Start seaghi-account: `make account-start`

## Shop

- Init seaghi-shop: `make shop-init`
- Start seaghi-shop: `make shop-start`

## Battle

- Init seaghi-battle: `make battle-init`
- Start seaghi-battle: `make battle-start`

Start the consumer in the directory seaghi-battle

```
bin/console messenger:consume async
```

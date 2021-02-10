#!/bin/bash
docker-compose run \
  --entrypoint /usr/bin/composer \
  --no-deps \
  --rm \
  -e COMPOSER_ALLOW_SUPERUSER=1 \
  gam \
  $@

#!/bin/bash
docker-compose run \
  --entrypoint /bin/bash \
  --no-deps \
  --rm \
  rest

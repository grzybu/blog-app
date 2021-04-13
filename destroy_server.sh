#!/bin/bash

docker-compose -f docker/docker-compose.yml exec gfgproduct rm -rf vendor
docker-compose -f docker/docker-compose.yml down -v

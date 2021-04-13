#!/bin/bash

docker-compose -f docker/docker-compose.yml exec blogapp rm -rf vendor
docker-compose -f docker/docker-compose.yml down -v --remove-orphans

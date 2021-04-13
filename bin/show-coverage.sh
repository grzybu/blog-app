#!/bin/bash

docker-compose -f docker/docker-compose.yml exec blogapp php composer.phar check

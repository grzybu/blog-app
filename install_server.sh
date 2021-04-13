#!/bin/bash

docker-compose -f ./docker/docker-compose.yml up -d --build
docker-compose -f ./docker/docker-compose.yml exec blogapp php composer.phar install --dev
docker-compose -f ./docker/docker-compose.yml exec blogdb mysql -uuser -ppassword -e "$(cat data/sql/init_database.sql)"

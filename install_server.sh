#!/bin/bash

docker-compose -f ./docker/docker-compose.yml up -d --build
docker-compose -f ./docker/docker-compose.yml exec gfgproduct php composer.phar install --dev
docker-compose -f ./docker/docker-compose.yml exec gfgdb mysql -uuser -ppassword -e "$(cat data/sql/init_database.sql)"

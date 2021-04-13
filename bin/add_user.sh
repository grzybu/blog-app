#!/bin/bash

docker-compose -f docker/docker-compose.yml exec blogapp php console.php AddUser $1 $2

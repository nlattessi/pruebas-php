#!/usr/bin/env bash
cd `dirname $0` \
&& docker-compose up -d \
&& docker run --link olx-nginx:nginx --net phpolxv2_default -v $(pwd):/var/www/html --rm phpunit/phpunit -c /var/www/html/phpunit-docker.xml \
&& docker-compose kill
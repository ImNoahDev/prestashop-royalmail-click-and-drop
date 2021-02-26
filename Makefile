cs:
	docker-compose -f .docker/compose.yml run shop vendor/bin/php-cs-fixer fix
cs-dry:
	docker-compose -f .docker/compose.yml run shop vendor/bin/php-cs-fixer fix --dry-run
test:
	docker-compose -f .docker/compose.yml run shop vendor/bin/phpunit
start:
	docker-compose -f .docker/compose.yml up -d
stop:
	docker-compose -f .docker/compose.yml down
build:
	docker-compose -f .docker/compose.yml build
php:
	docker-compose -f .docker/compose.yml run shop /bin/bash
presta:
	docker run -it --rm -v %cd%:/var/www/html/modules/royalmailclickanddrop -w /var/www/html/modules/royalmailclickanddrop prestashop/prestashop:1.7 /bin/bash
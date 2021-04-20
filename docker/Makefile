#!make
include .env
export $(shell sed 's/=.*//' .env)


init_cert:
	./init-letsencrypt.sh
	@echo "Cert ready!"


init:
	docker-compose pull
	docker-compose up -d --build
	docker-compose exec php-fpm chmod -R 777 storage/
	docker-compose exec php-fpm chmod -R 777 bootstrap/
	docker-compose exec php-fpm php -r "file_exists('.env') || copy('.env.example', '.env');"
	docker-compose exec php-fpm composer install --ignore-platform-reqs
	docker-compose exec php-fpm composer dump-autoload
	docker-compose exec php-fpm php artisan package:discover --ansi
	docker-compose exec php-fpm php artisan key:generate --ansi
	docker-compose exec php-fpm php artisan jwt:secret
	docker-compose exec php-fpm php artisan optimize
	docker-compose exec php-fpm php artisan migrate
	docker-compose exec php-fpm php artisan db:seed
	docker-compose exec php-fpm php artisan storage:link

	@echo "System ready!"

update:
	docker-compose pull
	docker-compose up -d
	docker-compose exec php-fpm composer install --ignore-platform-reqs
	docker-compose exec php-fpm composer dump-autoload
	docker-compose exec php-fpm php artisan optimize
	docker-compose exec php-fpm php artisan migrate
	docker-compose restart

	@echo "System updated!"


build:
	docker-compose pull
	docker-compose up -d --build
	docker-compose exec php-fpm chmod -R 777 storage/
	docker-compose exec php-fpm chmod -R 777 bootstrap/
	docker-compose exec php-fpm php -r "file_exists('.env') || copy('.env.example', '.env');"
	docker-compose exec php-fpm composer install --ignore-platform-reqs --no-dev
	docker-compose exec php-fpm composer dump-autoload
	docker-compose exec php-fpm php artisan package:discover --ansi
	docker-compose exec php-fpm php artisan key:generate --ansi
	docker-compose exec php-fpm php artisan jwt:secret
	docker-compose exec php-fpm php artisan optimize
	docker-compose exec php-fpm php artisan migrate
	docker-compose exec php-fpm php artisan db:seed
	docker-compose exec php-fpm php artisan storage:link

	@echo "Build ready!"


update_web:
	docker-compose pull
	docker-compose up -d
	@echo "System updated"


test:
	@echo "Clear config cache:"
	docker-compose exec php-fpm php artisan config:clear

	@echo "Run unit tests:"
	docker-compose exec php-fpm php artisan test --parallel --testsuite=Unit

	@echo "Run feature tests:"
	docker-compose exec php-fpm php artisan test --parallel --testsuite=Feature

	@echo "Optimize cache files:"
	docker-compose exec php-fpm php artisan optimize

	@echo "Testing ended"


phpreport:
	docker-compose exec php-fpm php ./vendor/bin/phpmetrics --report-html=phpreport ./app

	@echo "Report generated"

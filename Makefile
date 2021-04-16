#!make

RED='\033[0;31m'         #  ${RED}
GREEN='\033[0;32m'       #  ${GREEN}
YELLOW='\033[0;33m'      #  ${GREEN}
BOLD='\033[1;m'          #  ${BOLD}
WARNING='\033[37;1;41m'  #  ${WARNING}
END_COLOR='\033[0m'      #  ${END_COLOR}
.PHONY: install clean all TAGS info check distclean

DOCKER_PATH=./docker
ENV_FILE_PATH=./docker/.env

DOCKER_COMPOSE_FILES=--env-file ${ENV_FILE_PATH} --f ${DOCKER_PATH}/docker-compose.yml -f ${DOCKER_PATH}/docker-compose.dev.yml

# -p key in docker-compose command is equivalent to --project-name
ifndef PROJECT_NAME
	PROJECT_NAME=loooop
endif

ifeq ($(MODE), PROD)
	DOCKER_COMPOSE_FILES=--env-file ${ENV_FILE_PATH} -f ${DOCKER_PATH}/docker-compose.yml -f ${DOCKER_PATH}/docker-compose.prod.yml
endif

export LOGO

all : help

phpcs: # Run codesniffer check
	@$(MAKE) --no-print-directory info
	@echo $(GREEN)"\nCode check...\n" ${END_COLOR}
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES) exec -T php-fpm ./vendor/bin/phpcs
	@$(MAKE)  --no-print-directory end
.PHONY: phpcs
	
phpcbf: # Run codesniffer style fix
	@$(MAKE) --no-print-directory info
	@echo $(GREEN)"\nCode fixing...\n" ${END_COLOR}
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES) exec -T php-fpm ./vendor/bin/phpcbf
	@$(MAKE)  --no-print-directory end
.PHONY: phpcbf

info: # Base info
	@echo ${BOLD}"*********************"${RED}POWERED BY LARAVEL${END_COLOR}"**************************"${END_COLOR}
	@echo "$$LOGO"
	@echo ${BOLD}"For getting all commands list run"${GREEN}"make help"${END_COLOR}"or"${GREEN}make listCommands${END_COLOR}${END_COLOR}
	@echo ${BOLD}"If you want start app run"${RED}"make runApp"${END_COLOR}"or"${RED}make run${END_COLOR}"when your containers stopped"${END_COLOR}
	@echo ${BOLD}"Lets go! Have a good codding time..."${END_COLOR}
	@echo ${BOLD}"**********************************************************"${END_COLOR}
.PHONY: info

restart: # Restart containers
	@$(MAKE)  --no-print-directory info
	@echo ${GREEN}"\nRestarting containers...\n" ${END_COLOR}
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES) restart
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES) ps
	@$(MAKE)  --no-print-directory end
.PHONY:restart

reload: # Stop, delete and build containers
	@$(MAKE)  --no-print-directory info
	@echo ${GREEN}"\nReloading containers...\n" ${END_COLOR}
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES) down
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES) up -d --build --force-recreate
	@$(MAKE)  --no-print-directory end
.PHONY:reload

upContainers: # up containers
	@$(MAKE)  --no-print-directory info
	@echo ${GREEN}"\nReloading containers...\n" ${END_COLOR}
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES) down
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES) up -d
	@$(MAKE)  --no-print-directory end
.PHONY:upContainers

stopRmContainers: # up containers
	@$(MAKE)  --no-print-directory info
	@echo ${GREEN}"\nStopping and removing containers...\n" ${END_COLOR}
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES) stop
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES) rm
	@$(MAKE)  --no-print-directory end
.PHONY:upContainers

down: # Stop, delete containers and volumes
	@$(MAKE)  --no-print-directory info
	@echo ${GREEN}"\nStoping containers and delete volumes...\n" ${END_COLOR}
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES) down -v --rmi all
	@$(MAKE)  --no-print-directory end
.PHONY:down

plainDown: # Stop and remove containers, networks, images, and volumes
	@$(MAKE)  --no-print-directory info
	@echo ${GREEN}"\nStop and remove containers...\n" ${END_COLOR}
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES) down
	@$(MAKE)  --no-print-directory end
.PHONY:plainDown

stop: # Stop containers
	@$(MAKE)  --no-print-directory info
	@echo ${GREEN}"\nStoping containers...\n" ${END_COLOR}
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES) stop
	@$(MAKE)  --no-print-directory end
.PHONY:stop

up : run # Run also build containers
.PHONY:up

run:  # Build containers and run
	@$(MAKE)  --no-print-directory info
	@echo ${GREEN}"\nBuild containers...\n" ${END_COLOR}
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES) up -d --build
	@$(MAKE)  --no-print-directory end
.PHONY:run

goToDB: # Go to database container bash console
	@$(MAKE)  --no-print-directory info
	@echo ${GREEN}"\nOpening db...\n" ${END_COLOR}
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES) exec database bash
.PHONY:goToDB


.PHONY:goToApp
goToApp: # Go to application container bash console
	@$(MAKE)  --no-print-directory info
	@echo ${GREEN}"\nOpening application...\n" ${END_COLOR}
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES) exec php-fpm bash

startApp: # Build application container
	@$(MAKE)  --no-print-directory info
	@echo ${GREEN}"\nBuild application...\n" ${END_COLOR}
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES) up -d --build php-fpm
	@$(MAKE)  --no-print-directory end
.PHONY:startApp

startDB: # Build database container
	@$(MAKE)  --no-print-directory info
	@echo ${GREEN}"\nBuild db...\n" ${END_COLOR}
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES) up -d --build database
	@$(MAKE)  --no-print-directory end
.PHONY:startDB

startServer: # Build nginx_backend container
	@$(MAKE)  --no-print-directory info
	@echo ${GREEN}"\nBuild server...\n" ${END_COLOR}
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES) up -d --build nginx_backend
	@$(MAKE)  --no-print-directory end
.PHONY:startServer

server: # Build nginx_backend container
	@$(MAKE)  --no-print-directory info
	@echo ${GREEN}"\nBuild server...\n" ${END_COLOR}
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES) exec nginx_backend sh
	@$(MAKE)  --no-print-directory end
.PHONY:server

serverLogs: logServer # Same action as logServer command
.PHONY:serverLogs

logServer: # Get logs for nginx_backend container
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES) logs --tail=200 -f nginx_backend
.PHONY:logServer

test: # Run feature tests
	@$(MAKE)  --no-print-directory info
	@echo ${GREEN}"\nRun feature tests...\n" ${END_COLOR}
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES) exec -T application ./vendor/bin/phpunit --testsuite Feature
.PHONY:test

fresh: # Drop all tables and re-run all migrations
	@$(MAKE)  --no-print-directory info
	@echo ${GREEN}"\nDrop all tables and re-run all migrations...\n" ${END_COLOR}
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES) exec -T php-fpm php artisan migrate:fresh --seed
	@$(MAKE)  --no-print-directory end
.PHONY:fresh

refresh: # Reset and re-run all migrations
	@$(MAKE)  --no-print-directory info
	@echo ${GREEN}"\nReset and re-run all migrations...\n" ${END_COLOR}
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES) exec -T php-fpm php artisan migrate:refresh --seed
	@$(MAKE)  --no-print-directory end
.PHONY:refresh

rollback: # Rollback the last database migration
	@$(MAKE)  --no-print-directory info
	@echo ${GREEN}"\nRollback the last database migration...\n" ${END_COLOR}
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES) exec -T php-fpm php artisan migrate:rollback
	@$(MAKE)  --no-print-directory end
.PHONY:rollback

reset: # Reset and re-run all migrations
	@$(MAKE)  --no-print-directory info
	@echo ${GREEN}"\nReset and re-run all migrations...\n" ${END_COLOR}
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES) exec -T php-fpm php artisan migrate:reset
	@$(MAKE)  --no-print-directory end
.PHONY:reset

migrationStatus: # Get status of migrations
	@$(MAKE)  --no-print-directory info
	@echo ${GREEN}"\nMigrations status...\n" ${END_COLOR}
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES) exec -T php-fpm php artisan migrate:status
	@$(MAKE)  --no-print-directory end
.PHONY:migrationStatus

seed: # Run seeds
	@$(MAKE)  --no-print-directory info
	@echo ${GREEN}"\nSeed database...\n" ${END_COLOR}
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES) exec -T php-fpm php artisan db:seed
	@$(MAKE)  --no-print-directory end
.PHONY:seed

migrate: # Migrate migrations
	@$(MAKE)  --no-print-directory info
	@echo ${GREEN}"\nRun migrations...\n" ${END_COLOR}
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES) exec -T php-fpm php artisan migrate
	@$(MAKE)  --no-print-directory end
.PHONY:migrate

require: # Require composer package in container, for install run make require PACKAGE_NAME=test
	@$(MAKE)  --no-print-directory info
	@echo ${GREEN}"\nInstalling new package "$(PACKAGE_NAME)"...\n" ${END_COLOR}
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES) exec -T php-fpm composer require $(PACKAGE_NAME)
	@$(MAKE)  --no-print-directory end
.PHONY: require

install: # Install composer packages in docker
	@$(MAKE)  --no-print-directory info
	@echo ${GREEN}"\nInstalling new packages...\n" ${END_COLOR}
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES) exec -T php-fpm composer install
	@$(MAKE)  --no-print-directory end
.PHONY: install

update: # Update composer packages in container
	@$(MAKE)  --no-print-directory info
	@echo ${GREEN}"\nUpdating packages...\n" ${END_COLOR}
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES) exec -T php-fpm composer update
	@$(MAKE)  --no-print-directory end
.PHONY: update

dumpAutoload: # Run composer command dump-autoload in container
	@$(MAKE)  --no-print-directory info
	@echo ${GREEN}"\nDump autoloading...\n" ${END_COLOR}
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES) exec -T php-fpm composer dump-autoload
	@$(MAKE)  --no-print-directory end
.PHONY: dumpAutoload

dal: dumpAutoload # Auto loading dump in container
.PHONY:dal

cmpu: # Update composer packages local
	@$(MAKE)  --no-print-directory info
	@echo ${GREEN}"\nUpdating packages...\n" ${END_COLOR}
	composer update
	@$(MAKE)  --no-print-directory end
.PHONY:cmpu

cmpda: # Run composer command dump-autoload
	@$(MAKE)  --no-print-directory info
	@echo ${GREEN}"\nDump autoloading...\n" ${END_COLOR}
	composer dump-autoload
	@$(MAKE)  --no-print-directory end
.PHONY:cmpda

testUnit: # Run unit tests
	@$(MAKE)  --no-print-directory info
	@echo ${GREEN}"\nRun unit tests...\n" ${END_COLOR}
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES) exec -T php-fpm ./vendor/bin/phpunit --testsuite Unit
.PHONY:testUnit

testFeature: # Run feature tests
	@$(MAKE)  --no-print-directory info
	@echo ${GREEN}"\nRunning feature tests...\n" ${END_COLOR}
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES) exec -T php-fpm ./vendor/bin/phpunit --testsuite Feature
.PHONY:testFeature

status: # Get list docker containers in system
	@$(MAKE)  --no-print-directory info
	@echo ${GREEN}"\nContainers \n" ${END_COLOR}
	@docker ps
	@$(MAKE)  --no-print-directory end
.PHONY:status

clear: # Clear laravel cached configs and routes
	@$(MAKE)  --no-print-directory info
	@echo ${GREEN}"\n Clear caches \n" ${END_COLOR}
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES) exec -T php-fpm php artisan cache:clear
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES) exec -T php-fpm php artisan config:clear
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES) exec -T php-fpm php artisan event:clear
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES) exec -T php-fpm php artisan optimize:clear
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES) exec -T php-fpm php artisan route:clear
	@$(MAKE)  --no-print-directory end
.PHONY:clear

cache: # Cache laravel configs and routes
	@$(MAKE)  --no-print-directory info
	@echo ${GREEN}"\n Cache configs \n" ${END_COLOR}
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES) exec -T php-fpm php artisan route:cache
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES) exec -T php-fpm php artisan event:cache
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES) exec -T php-fpm php artisan config:cache
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES) exec -T php-fpm php artisan package:discover
	@$(MAKE)  --no-print-directory end
.PHONY:cache

packageDiscover: # Discover laravel packages
	@$(MAKE)  --no-print-directory info
	@echo ${GREEN}"\n Discover packages \n" ${END_COLOR}
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES) exec -T php-fpm php artisan package:discover
	@$(MAKE)  --no-print-directory end
.PHONY:packageDiscover

redisFlush: # Delete all data from redis
	@$(MAKE)  --no-print-directory info
	@echo ${GREEN}"\n Redis clearing \n" ${END_COLOR}
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES) exec -T redis redis-cli FLUSHALL
	@$(MAKE)  --no-print-directory end
.PHONY:redisFlush

testApp: # Run feature tests
	@$(MAKE)  --no-print-directory info
	@echo ${GREEN}"\nRunning feature tests...\n" ${END_COLOR}
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES) exec -T php-fpm ./vendor/bin/phpunit --testsuite Feature
.PHONY:testApp

runLocal: runApp # Run application local
.PHONY:runLocal

appRun: runApp # Same action as command `make runApp`
.PHONY:appRun

runApp: # Run backend with migration and tests
	@$(MAKE)  --no-print-directory info
	@echo ${GREEN}"\nSTARTING...\n" ${END_COLOR}
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES) down
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES) up -d --build
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES) exec -T php-fpm rm -R storage/application || true
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES) exec -T php-fpm mkdir storage/application
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES) exec -T php-fpm mkdir storage/application/documents
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES) exec -T php-fpm chmod -R 777 storage
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES) exec -T php-fpm composer install
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES) exec -T php-fpm composer dump-autoload
	@sleep 15
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES) exec -T php-fpm php artisan migrate:fresh
	@echo ${GREEN}"\nFINISHED\n" ${END_COLOR}
	@$(MAKE)  --no-print-directory end
.PHONY:runApp

socketLogs: # Get socket server logs
	@$(MAKE)  --no-print-directory info
	@echo ${GREEN}"\Getting socket server logs...\n" ${END_COLOR}
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES)  logs --tail=200 -f laravel-echo
.PHONY:socketLogs

appLogs: # Get app logs
	@$(MAKE)  --no-print-directory info
	@echo ${GREEN}"\nGetting app logs...\n" ${END_COLOR}
	tail -f storage/logs/laravel.log -n 200
.PHONY:appLogs

ps: # List all containers
	@$(MAKE)  --no-print-directory info
	@echo ${GREEN}"\nContainers \n" ${END_COLOR}
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES) ps
	@$(MAKE)  --no-print-directory end
.PHONY:ps

listCommands help: # List all command call make help or make listCommands ## '^[^\.#[:space:]].*:' - old regex save, maybe useful in future ##
	@$(MAKE)  --no-print-directory info
	@echo ${GREEN}"\nHELP INFORMATION:\n" ${END_COLOR}
	@GREP_COLOR='01;33' grep --color=always  '^[^\.^[:space:]][^:]*:' Makefile | sed G
	@$(MAKE)  --no-print-directory end
.PHONY:listCommands

networks: # Get list ip addresses of containers
	@$(MAKE)  --no-print-directory info
	@echo ${GREEN}"\nSERVER CONTAINERS IP's:\n" ${END_COLOR}
	@docker inspect -f '{{range .NetworkSettings.Networks}}{{.IPAddress}}{{end}} .{{.Name}}' $(docker ps -qa)
	@$(MAKE)  --no-print-directory end
.PHONY:networks

ngrokLogs: # Getting logs from ngrok service
	@$(MAKE)  --no-print-directory info
	@echo ${GREEN}"\nNGROK TUNNEL CONTAINER LOGS:\n" ${END_COLOR}
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES)  logs --tail=200 -f ngroktunnel
.PHONY:ngrokLogs

buildNgrok: # Build ngroktunnel container
	@$(MAKE)  --no-print-directory info
	@echo ${GREEN}"\nSTARTING...\n" ${END_COLOR}
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES)  build --no-cache ngroktunnel
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES)  up -d --force-recreate ngroktunnel
	@echo ${GREEN}"\nFINISHED\n" ${END_COLOR}
	@$(MAKE)  --no-print-directory end
.PHONY:buildNgrok

updateApp: # Refresh application container code
	@$(MAKE)  --no-print-directory info
	@echo ${GREEN}"\nSTARTING...\n" ${END_COLOR}
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES) down
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES) up -d --build
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES) exec -T php-fpm rm -R storage/application || true
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES) exec -T php-fpm mkdir storage/application
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES) exec -T php-fpm mkdir storage/application/documents
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES) exec -T php-fpm chmod -R 777 storage
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES) exec -T php-fpm composer update
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES) exec -T php-fpm composer dump-autoload
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES) exec -T php-fpm php artisan migrate:fresh --seed
	@echo ${GREEN}"\nFINISHED\n" ${END_COLOR}
	@$(MAKE)  --no-print-directory end
.PHONY:updateApp

featureTestsFilter: # Run one test class, for example: make featureTestsFilter f=AdminApiTest
	@$(MAKE)  --no-print-directory info
	@echo ${GREEN}"\nRunning game tests \n" ${END_COLOR}
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES) exec -T php-fpm ./vendor/bin/phpunit --testsuite Feature --filter $(f)
.PHONY:featureTestsFilter

unitTestsFilter: # Run one test class, for example: make unitTestsFilter f=AdminApiTest
	@$(MAKE)  --no-print-directory info
	@echo ${GREEN}"\nRunning game tests \n" ${END_COLOR}
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES) exec -T php-fpm ./vendor/bin/phpunit --testsuite Unit --filter $(f)
.PHONY:unitTestsFilter

vendorPublish: # Publish vendor configs
	@$(MAKE)  --no-print-directory info
	@echo ${GREEN}"\nPublishing...\n" ${END_COLOR}
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES) exec -T php-fpm php artisan vendor:publish
.PHONY:vendorPublish

reloadFront: # Reloading front containers
	@$(MAKE)  --no-print-directory info
	@echo ${GREEN}"RELLOADING...\n" ${END_COLOR}
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES) pull
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES)  up -d --force-recreate front
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES)  up -d --force-recreate dashboard
	@echo ${GREEN}"\nFINISHED\n" ${END_COLOR}
	@$(MAKE)  --no-print-directory end
.PHONY:reloadFront

reloadQueues: # Reloading queues containers
	@$(MAKE)  --no-print-directory info
	@echo ${GREEN}"\nSTARTING...\n" ${END_COLOR}
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES)  build --no-cache queue_worker queue_worker_jobs queue_worker_tokens
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES)  up -d --force-recreate queue_worker queue_worker_jobs queue_worker_tokens
	@echo ${GREEN}"\nFINISHED\n" ${END_COLOR}
	@$(MAKE)  --no-print-directory end
.PHONY:reloadQueues

stopQueues: # Stop queues containers
	@$(MAKE)  --no-print-directory info
	@echo ${GREEN}"\nSTARTING...\n" ${END_COLOR}
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES)  stop queue_worker queue_worker_jobs queue_worker_tokens
	@echo ${GREEN}"\nFINISHED\n" ${END_COLOR}
	@$(MAKE)  --no-print-directory end
.PHONY:stopQueues

buildFront: # Build front container
	@$(MAKE)  --no-print-directory info
	@echo ${GREEN}"\nSTARTING...\n" ${END_COLOR}
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES)  build --no-cache front
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES)  up -d --force-recreate front
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES)  build --no-cache dashboard
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES)  up -d --force-recreate dashboard
	@echo ${GREEN}"\nFINISHED\n" ${END_COLOR}
	@$(MAKE)  --no-print-directory end
.PHONY:buildFront

buildNoCache: # Build containers without caches
	@$(MAKE)  --no-print-directory info
	@echo ${GREEN}"\nSTARTING...\n" ${END_COLOR}
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES)  build --no-cache
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES)  up -d --force-recreate
	@echo ${GREEN}"\nFINISHED\n" ${END_COLOR}
	@$(MAKE)  --no-print-directory end
.PHONY:buildNoCache

buildPgadmin: # Build PhpMyAdmin container and start
	@$(MAKE)  --no-print-directory info
	@echo ${GREEN}"\nSTARTING...\n" ${END_COLOR}
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES)  build --no-cache pgadmin
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES)  up -d --force-recreate pgadmin
	@echo ${GREEN}"\nFINISHED\n" ${END_COLOR}
	@$(MAKE)  --no-print-directory end
.PHONY: buildPhPMyAdmin

stopPgadmin: # Stop PhpMyAdmin container
	@$(MAKE)  --no-print-directory info
	@echo ${GREEN}"\nSTOPING...\n" ${END_COLOR}
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES) stop pgadmin
	@echo ${GREEN}"\nFINISHED\n" ${END_COLOR}
	@$(MAKE)  --no-print-directory end
.PHONY: stopPhPMyAdmin

buildRedisCommander: # Build redis-commander container and start
	@$(MAKE)  --no-print-directory info
	@echo ${GREEN}"\nSTARTING...\n" ${END_COLOR}
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES)  build --no-cache redis-commander
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES)  up -d --force-recreate redis-commander
	@echo ${GREEN}"\nFINISHED\n" ${END_COLOR}
	@$(MAKE)  --no-print-directory end
.PHONY: buildRedisCommander

stopRedisCommander: # Stop redis-commander container
	@$(MAKE)  --no-print-directory info
	@echo ${GREEN}"\nSTOPING...\n" ${END_COLOR}
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES) stop redis-commander
	@echo ${GREEN}"\nFINISHED\n" ${END_COLOR}
	@$(MAKE)  --no-print-directory end
.PHONY: stopRedisCommander

buildProxy: # Build nginx proxy container and start
	@$(MAKE)  --no-print-directory info
	@echo ${GREEN}"\nSTARTING...\n" ${END_COLOR}
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES)  build --no-cache nginx_proxy
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES)  up -d --force-recreate nginx_proxy
	@echo ${GREEN}"\nFINISHED\n" ${END_COLOR}
	@$(MAKE)  --no-print-directory end
.PHONY: buildProxy

buildApplication: # Build application container and start
	@$(MAKE)  --no-print-directory info
	@echo ${GREEN}"\nSTARTING...\n" ${END_COLOR}
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES)  build --no-cache php-fpm
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES)  up -d --force-recreate php-fpm
	@echo ${GREEN}"\nFINISHED\n" ${END_COLOR}
	@$(MAKE)  --no-print-directory end
.PHONY: buildApplication

stopProxy: # Stop nginx proxy container
	@$(MAKE)  --no-print-directory info
	@echo ${GREEN}"\nSTOPING...\n" ${END_COLOR}
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES) stop nginx_proxy
	@echo ${GREEN}"\nFINISHED\n" ${END_COLOR}
	@$(MAKE)  --no-print-directory end
.PHONY: stopProxy

logsProxy: # Logs nginx proxy container
	@$(MAKE)  --no-print-directory info
	@echo ${GREEN}"\nSTOPING...\n" ${END_COLOR}
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES) logs nginx_proxy
	@echo ${GREEN}"\nFINISHED\n" ${END_COLOR}
	@$(MAKE)  --no-print-directory end
.PHONY: logsProxy

logsCron: # Logs cron container
	@$(MAKE)  --no-print-directory info
	@echo ${GREEN}"\nLogs...\n" ${END_COLOR}
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES) logs cron
	@$(MAKE)  --no-print-directory end
.PHONY: logsCron

goToCron: # Open cron container
	@$(MAKE)  --no-print-directory info
	@echo ${GREEN}"\nCron...\n" ${END_COLOR}
	@docker-compose -p $(PROJECT_NAME) $(DOCKER_COMPOSE_FILES) exec cron bash
	@$(MAKE)  --no-print-directory end
.PHONY: goToCron

end: # Print something
	@echo "\n---------------------"${YELLOW}"BLOODBIN"${END_COLOR}"2021 ----------------------\n"
.PHONY:end

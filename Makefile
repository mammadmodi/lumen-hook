prepare:
	composer install

build-images: prepare
	@echo "building application docker image"
	docker build -t hook:latest -f Dockerfile .
	@echo "application docker image has been built successfully"
	@echo "building scheduler docker image"
	docker build -t hook:latest-scheduler -f Dockerfile.scheduler .
	@echo "scheduler docker image has been built successfully"

up: down build-images
	@echo "waiting for create all services ..."
	docker-compose up -d
	@echo "waiting for services to set up ..."
	sleep 20
	@echo "migrating database ..."
	docker-compose exec api php artisan migrate
	@echo "database migrated."

down:
	docker-compose down

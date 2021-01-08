current-dir := $(dir $(abspath $(lastword $(MAKEFILE_LIST))))

# 🐘 Composer
composer-env-file:
	@if [ ! -f .env ]; then cp $(current-dir).env.dist $(current-dir).env; fi

# 🔍 Test
.PHONY: test
test: composer-env-file
	docker exec -it quotes-php ./vendor/bin/phpspec run
	docker exec -it quotes-php ./vendor/bin/behat

# 🐳 Docker Compose
.PHONY: start
start: composer-env-file
	docker-compose up --build -d
	docker exec quotes-php composer install --ignore-platform-reqs --no-ansi

.PHONY: down
down: composer-env-file
	docker-compose down

.PHONY: dev
dev: composer-env-file
	docker-compose up --build -d
	docker exec quotes-php composer install --ignore-platform-reqs --no-ansi
	docker exec quotes-php php config/app/dev/init.php

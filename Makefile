docker-up:
	docker compose up -d

docker-down:
	docker compose down --remove-orphans

cli:
	docker compose exec -it php-cli bash

composer-dump:
	docker compose run --rm php-cli composer dump-autoload

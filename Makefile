#Makefile
install:
	docker compose up --build -d nginx
	docker compose exec -it php composer install
	docker compose exec -it php php artisan key:generate
	docker compose exec -it php php artisan migrate
	docker compose run --rm npm run build

refresh:
	docker compose exec -it php php artisan migrate:refresh --seed

up:
	docker compose up -d nginx
	make clear

down:
	make clear
	docker compose down

clear:
	docker compose exec -it php php artisan cache:clear
	docker compose exec -it php php artisan routes:clear
	docker compose exec -it php php artisan optimize:clear


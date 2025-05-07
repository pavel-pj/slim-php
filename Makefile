install:
	composer update
	composer install
sa:
	npm run sass:build
saw:
	npm run sass:watch
test:
	composer exec --verbose phpunit tests
	composer validate
up-ul:
	composer dump-autoload
lint:
	composer exec --verbose phpcs -- --standard=PSR12 src public
PORT ?= 8000
start:
	php -S 0.0.0.0:$(PORT) -t public public/index.php


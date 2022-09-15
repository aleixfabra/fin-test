install:
	echo "Installing mock server for frontend development purposes..."
	cd frontend_server && npm install;
	echo "Finished, run 'make start' for starting the mocked server"

start:
	echo "Launching mocked server, visit http://localhost:3000"
	cd frontend_server && npm start

deps:
	composer install
	yarn --cwd apps/frontend install

schema:
	./bin/console doctrine:database:create
	./bin/console doctrine:schema:create

fixtures:
	./bin/console  doctrine:fixtures:load

lint:
	./vendor/bin/php-cs-fixer fix src
	./vendor/bin/php-cs-fixer fix tests
	yarn --cwd apps/frontend lint --fix

test:
	./bin/phpunit
	./vendor/bin/behat

test-schema:
	./bin/console --env=test doctrine:database:create
	./bin/console --env=test doctrine:schema:create

frontend-dev-server:
	yarn --cwd apps/frontend serve -- --port 8080

backend-dev-server:
	symfony server:start --port 8000

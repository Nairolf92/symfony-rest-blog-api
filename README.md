# Symfony Rest Blog API + Docker mysql

## Install
* git clone project
* `docker-compose up -d`
* `composer install`
* `php bin/console server:run `
* `php bin/console doctrine:database:create`
* `php bin/console doctrine:schema:update --force`
* `php bin/console doctrine:fixtures:load`
* `mkdir -p config/jwt`
* `openssl genrsa -out config/jwt/private.pem -aes256 4096`
* `openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem`
* connect to /admin with toto@toto.com and toto
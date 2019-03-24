# Symfony Rest Blog API + Docker mysql

## Install
* git clone project
* `docker-compose up -d`
* Modify `.env.local` file and replace/add 
    * `DATABASE_URL=mysql://root:root@127.0.0.1:3307/symfony-rest-blog-api`
    * `JWT_PASSPHRASE=toto`
* `composer install`
* `php bin/console doctrine:database:create`
* `php bin/console doctrine:migrations:migrate`
* `php bin/console doctrine:fixtures:load`
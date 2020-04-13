angular-symfony [![Build Status](https://travis-ci.org/FlyersWeb/angular-symfony.svg?branch=master)](https://travis-ci.org/FlyersWeb/angular-symfony)
===============

Project Bootstrap for an Angular 2+ and Symfony 4+ webservices project.

Introduction
------------

This project is a template application with a secured RestFul API communication via JWT security scheme.

Buy me a coffee
---------------

[![Buy me a coffee](https://raw.githubusercontent.com/FlyersWeb/angular-symfony/master/buy-me-a-coffee.png)](https://paypal.me/nac1dbois)

I'm working on this project in my free time and offering it free of charges. To help me work more on this you can send me tips to buy more coffee :)

Installation
------------

Install docker and docker-compose.

Clone the project :

	git clone git@github.com:FlyersWeb/angular-symfony.git

Launch dockerized environment :

	docker-compose up -d

Log in application docker image :

	docker-compose exec application bash

Install dependencies :

	composer install

Create database if necessary :

  php bin/console doctrine:database:create

Create schemas (FOSUserBundle) :

	php bin/console doctrine:schema:create

Create and activate user :

	php bin/console doctrine:fixtures:load

Access the front end using port 4200 :

	firefox http://localhost:4200 &

Launching tests
---------------

If you want to contribute to project you'll need to have tests to pass. So in order to run them you'll need to :

Log in application docker image :

	docker-compose exec application bash

Update database connection information in `.env.test`

Create database :

  php bin/console doctrine:database:create --env=test

Create schemas (FOSUserBundle) :

	php bin/console doctrine:schema:create --env=test

Create and activate user :

	php bin/console doctrine:fixtures:load --env=test

Copy Phpunit config :

  cp phpunit.xml.dist phpunit.xml

Launch tests using :

  bin/phpunit

Authentication system
---------------------

The Authentication system is based on the JWT token as implemented by [Lexik](https://github.com/lexik/LexikJWTAuthenticationBundle)

User management is done through [FOSUserBundle](https://github.com/FriendsOfSymfony/FOSUserBundle), you can easily add / edit / delete users by using their API.

The server provides a Rest API using [FOSRestBundle](https://github.com/FriendsOfSymfony/FOSRestBundle) allowing you to connect using the following query: 

`curl -X POST -H "Content-Type: application/json" http://localhost:8000/api/login_check -d '{"username":"bob","password":"Abc123"}'`

Client Side specifics
---------------------

On the client side, I've inspired my code from Angular official documentation about HttpInterceptor, allowing me to send the JWT Token on each HTTP request when token is available.

The token is sent in *Authorization* headers: 

`Authorization: Bearer xxx`

LICENSE
-------

This program is free software. It comes without any warranty, to the extent permitted by applicable law.

This software is LICENSED under the MIT License. Use it at your own risk.

WARNING
-------

Servers are configured for developments purposes. Do not deploy this project on production as is. You should have a look to [Symfony deployment documentation](https://symfony.com/doc/4.4/deployment.html) for the Back-end and the [Angular deployment documentation](https://angular.io/guide/deployment) for the Front-End part.

You should also change the preconfigured keys for signatures by generating your own keys using :

  openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
  openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout

And copy the passphrase into the field `JWT_PASSPHRASE` in `.env` file.

You should also update the `APP_SECRET` in `.env` file.

Conclusion
----------

You can use this template and adapt it to your needs.

@FlyersWeb

angular-symfony [![Build Status](https://travis-ci.org/FlyersWeb/angular-symfony.svg?branch=master)](https://travis-ci.org/FlyersWeb/angular-symfony)
===============

Project Bootstrap for an Angular 2+ and Symfony 4+ webservices project.

Introduction
------------

This project is a template application with a secured RestFul API communication via WSS UserToken security scheme.

Buy me a coffee
---------------

[![Buy me a coffee](https://raw.githubusercontent.com/FlyersWeb/angular-symfony/master/buy-me-a-coffee.png)](https://paypal.me/nac1dbois)

I'm working on this project in my free time and offering it free of charges. To help me work more on this you send me tips to buy more coffee :)

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

Update schemas (FOSUserBundle) :

	php bin/console doctrine:schema:create

Create and activate user :

	php bin/console doctrine:fixtures:load

Access the front end using port 4200 :

	firefox http://localhost:4200 &

Authentication system
---------------------

The Authentication system is based on the custom Authentication Provider of the [Symfony Cookbook](https://symfony.com/doc/4.4/security/custom_authentication_provider.html)

> The following chapter demonstrates how to create a custom authentication provider for WSSE authentication. The security protocol for WSSE provides several security benefits:
> * Username / Password encryption
> * Safe guarding against replay attacks
> * No web server configuration required
>
> WSSE is very useful for the securing of web services, may they be SOAP or REST.

I used the exact same authentication system.

Client Side specifics
---------------------

On the client side, I've inspired my code from Angular official documentation about HttpInterceptor, allowing me to send the WSS UserToken on each HTTP request when token is available. Examples usually shows how to send the Authorization header.

The difference there is that I send the token, username and user digest in the HTTP Header *X-WSSE*.

LICENSE
-------

This program is free software. It comes without any warranty, to the extent permitted by applicable law.

This software is LICENSED under the MIT License. Use it at your own risk.

WARNING
-------

Servers are configured for developments purposes. Do not deploy this project on production as is. You should have a look to [Symfony deployment documentation](https://symfony.com/doc/4.4/deployment.html) for the Back-end and the [Angular deployment documentation](https://angular.io/guide/deployment) for the Front-End part.

Conclusion
----------

You can use this template and adapt it to your needs.

@FlyersWeb

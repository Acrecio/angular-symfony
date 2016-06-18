angular-symfony
===============

Project Bootstrap for an angularJS + Symfony webservices project.

Introduction
------------

This project is a template application with secured communication via a RestFul API between the client part with AngularJS and the server part with Symfony2.

Installation
------------

Install docker and docker-compose, refer to docker documentation.

Clone the project :

	git clone git@github.com:FlyersWeb/angular-symfony.git angular-symfony

Launch dockerized environment :

  docker-compose up -d

Log in application docker image :

  docker exec -it dockerify_application_1 bash

Update schemas (FOSUserBundle) :

 	php app/console doctrine:schema:create

Create and activate user :

	php app/console fos:user:create admin admin@foo.com admin
	php app/console fos:user:activate admin

Clean old nonce if necessary :

	php app/console escape:wsseauthentication:nonces:delete wsse_secured

Access the front end using port 8080 :

	firefox http://localhost:8080 &

Authentication system
---------------------

The Authentication system is based on the custom Authentication Provider of the Symfony2 Cookbook : http://symfony.com/doc/2.1/cookbook/security/custom_authentication_provider.html

> The following chapter demonstrates how to create a custom authentication provider for WSSE authentication. The security protocol for WSSE provides several security benefits:
> * Username / Password encryption
> * Safe guarding against replay attacks
> * No web server configuration required
>
> WSSE is very useful for the securing of web services, may they be SOAP or REST.

I used the exact same authentication system with a little change in moment of generating the digest, we use the hexadecimal value of the hashed seed in lieu of the binary value.

Client Side specifics
---------------------

On the client side, I've inspired my code from Nils Blum-Oeste article explaining how to send an authorization token for every request. To do this you have to register a wrapper for every resource actions that execute a specific code before doing the action. For more information you can check http://nils-blum-oeste.net/angularjs-send-auth-token-with-every--request/.

The differences there is that I send the token, username and user digest in the HTTP Header *X-WSSE*.

Conclusion
----------

You can use this template and adapt it to your needs.

@FlyersWeb

angular-symfony
===============

Project Bootstrap for an angularJS + Symfony webservices project.

Introduction
------------

This project is a template application with secured communication via a RestFul API between the client part with AngularJS and the server part with Symfony2.

Installation
------------

Clone the project :

git clone git@github.com:FlyersWeb/angular-symfony.git angular-symfony

Update packages :

cd angular-symfony
composer.phar update

Create cache and logs folders :

mkdir app/cache
mkdir app/logs
chmod -R 777 app/cache
chmod -R 777 app/logs

Link project to your webserver and access it :

ln -snf ./ /var/www/html/angular-symfony
firefox http://localhost/angular-symfony/ &

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



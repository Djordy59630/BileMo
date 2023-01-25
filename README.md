# BileMo

Init project
--------------------------------------------
1 - Clone this repository
2 - configure your environment in .env.local
3 - make "composer install"
4 - make php "bin/console doctrine:fixtures:load" ( you need to be in development environment ) 
5 - symfony server:start

Authentification
--------------------------------------------
( if you use postman for try this app, add in headers "Content-Type : application/json" )

1 - url for token method [POST] : http://127.0.0.1:8000/api/login_check 
  - in body add : 
                {
                  "username": "client-081@test.com",
                  "password": "client-081"
                }

2 - copy Token and from now you must use this token everywhere

Api route :
--------------------------------------------

consult the list of BileMo products;

    [GET] - http://127.0.0.1:8000/api/phone/
  
consult the details of a BileMo product;
    [GET] - http://127.0.0.1:8000/api/phone/{id}
    
consult the list of registered users linked to a client on the website;

consult the details of a registered user linked to a client;

add a new user linked to a customer;

delete a user added by a customer.

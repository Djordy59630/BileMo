# BileMo

Init project
--------------------------------------------
1 - Clone this repository <br/>
2 - configure your environment in .env.local  <br/>
3 - make "composer install"  <br/>
4 - Create database "php bin/console d:d:c" <br/>
5 - use migrations "php bin/console d:m:m" <br/>
6 - make "php bin/console doctrine:fixtures:load" ( you need to be in development environment )   <br/>
7 - generate keys for jwt auth "php bin/console lexik:jwt:generate-keypair"  <br/>
8 - symfony server:start  <br/>

Authentification
--------------------------------------------
( if you use postman for try this app, add in headers "Content-Type : application/json" )  <br/>

1 - url for token method [POST]:

   http://127.0.0.1:8000/api/login_check  

  - in body add :
  
                {
                  "username": "client-081@test.com",
                  "password": "client-081" 
                }

2 - copy Token and from now you must use this token everywhere <br/>

Api route :
--------------------------------------------

consult the list of BileMo products;

    [GET] - http://127.0.0.1:8000/api/phone/
  
consult the details of a BileMo product;

    [GET] - http://127.0.0.1:8000/api/phone/{id}
    
consult the list of registered users linked to a client on the website;

    [GET] - http://127.0.0.1:8000/api/user/

consult the details of a registered user linked to a client;

    [GET] - http://127.0.0.1:8000/api/user/{id}

add a new user linked to a customer;

    [POST] - http://127.0.0.1:8000/api/user/

delete a user added by a customer.

    [DELETE] - http://127.0.0.1:8000/api/user/{id}
   
Documentations

    [GET] - http://127.0.0.1:8000/api/user/api/doc

Status codes
-----------------
200 : OK <br/>
201 : Created <br/>
204 : No Content (delete) <br/>
400 : Bad Request <br/>
401 : Unauthorized <br/>
403 : Forbidden <br/>
404 : Not Found <br/>
409 : Conflict


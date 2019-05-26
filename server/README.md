# Symfony 4 JWT REST API example/boilerplate/demo

This is a boilerplate (on steroids) implementation of Symfony 4 REST API using JWT 
(JSON Web Token). It is created with best REST API practices in mind. 
REST API interaction more or less follows guidline/summary provided by this excellent 
article: https://blog.mwaysolutions.com/2014/06/05/10-best-practices-for-better-restful-api/

Regarding project itself. Several ideas were in mind, like thin-controller and TDD approach 
(this project was mainly created by first creating tests and then actual code using 
red-green-refactor technique). SOLID principles, speaking names and other good design 
practices were also kept in mind (thankfully Symfony itself is a good primer of this). 
Most business logic is moved from controllers to corresponding services, 
which in turn use other services and Doctrine repositories to execute various DB queries.

That said, there is always room for improvement, so use it as a starting point and modify
according to your requirements.

## What this REST API is doing?

This is a simple football teams and leagues managing app, which is implemented as REST API which uses 
JWT (JSON Web Token) tokens to access various endpoints. 
You can add football leagues, assign football teams to it, modify some particular football team 
and remove football teams and leagues. This is a simple project which is used to demonstrate 
how to create REST API services and secure access to endpoints using JWT tokens. 
See "Usage/testing" section.

## Technical details / Requirements:
- Current project is built using Symfony 4.1 framework
- It is based on microservice/API symfony project (symfony/skeleton)
	- https://symfony.com/download
- PHPUnit is used for tests	
	* Note: it is better to run symfony's built-in PHPUnit, not the global one you have on your system, 
			  because different versions of PHPUnit expect different syntax. Tests for this project 
			  were built using preinstalled PHPUnit which comes with Symfony (located in bin folder). 
			  You can run all tests by running this command from project directory: 
			  ./bin/phpunit (php bin/phpunit on Windows). 
			  * Read more here: https://symfony.com/doc/current/testing.html			 
- PHP 7.2.6 is used so you will need something similar available on your system (there are many options to install it: Docker/XAMPP/standalone version etc.)
- MariaDB (MySQL) is required (10.1.33-MariaDB was used during development)
- Guzzle composer package is used to test REST API endpoints

## Installation:
	
    - git clone https://github.com/vgrankin/symfony_4_jwt_restapi_demo.git
    
    - go to project directory and run: composer install
    
    * at this point make sure MySQL is installed and is running	
    - open .env filde in project directory (copy .env.dist and create .env file out of it (in same location) if not exists)
    
    - configure DATATABSE_URL
        - This is example of how my .env config entry looks: DATABASE_URL=mysql://root:@127.0.0.1:3306/football # user "root", no db pass
    * more infos:
        - https://symfony.com/doc/current/configuration.html#the-env-file-environment-variables
        - https://symfony.com/doc/current/doctrine.html#configuring-the-database
        - https://symfony.com/doc/current/configuration/environments.html
        
    - go to project directory and run following commands to create database using Doctrine:
        - php bin/console doctrine:database:create (to create database called `football`, it will figure out db name based on your DATABASE_URL config)		
        - php bin/console doctrine:schema:update --force (executes queries to create/update all Entities in the database in accordance to latest code)
        
        * example of command execution on Windows machine: C:\Users\admin\PhpProjects\symfony_restapi>php bin/console doctrine:database:create
        * you can preview SQL queries Doctrine will run (without actually executing queries). To do so, run: php bin/console doctrine:schema:update --dump-sql
        * if you need to start from scratch, you can drop database like this: php bin/console doctrine:database:drop --force
        * Run php bin/console list doctrine to see a full list of commands available.
        
    - In order to run PHPUnit tests yourself, you will need to create local version of phpunit.xml:
        - for that, just copy phpunit.xml.dist and rename it to phpunit.xml
        - then add record to phpunit.xml which will tell Symfony which database server (and DB) you want to use specifically for tests:
            * add it right below where it says: "<!-- define your env variables for the test env here -->"
            <env name="DATABASE_URL" value="mysql://root:@127.0.0.1/football" /> <!-- this is how my config looks like -->
            * read more here: https://symfony.com/doc/4.0/testing/database.html

## Implementation details:
- In terms of workflow the following interaction is used: to get the job done for any given request usually something like this is happening: Controller uses Service (which uses Service) which uses Repository which uses Entity. This way we have a good thin controller along with practices like Separation of Concerns, Single responsibility principle etc.
- App\EventSubscriber\ExceptionSubscriber is used to process all Symfony-thrown exceptions and turn them into nice REST-API compatible JSON response (instead of HTML error pages shown by default in case of exception like 404 (Not Found) or 500 (Internal Server Error))
- App\Service\ResponseErrorDecoratorService is a simple helper to prepare error responses and to make this process consistent along the framework. It is used every time error response (such as status 400 or 404) is returned.
- HTTP status codes and REST API url structure is implemented in a way similar to described here (feel free to reshape it how you wish): https://blog.mwaysolutions.com/2014/06/05/10-best-practices-for-better-restful-api/
- In order to make any controller JWT secured (to make every action of it accessible only to authenticated users), it needs to implement TokenAuthenticatedController interface (Read here how this is possible: https://symfony.com/doc/current/event_dispatcher/before_after_filters.html) 
- All application code is in /src folder
- All tests are located in /tests folder
- In most cases the following test-case naming convention is used: MethodUnderTest____Scenario____Behavior()

## Usage/testing:

    First of all, start your MySQL server and PHP server. Here is example of how to start local PHP server on Windows 10:
    C:\Users\admin\PhpProjects\symfony_restapi>php -S 127.0.0.1:8000 -t public
    * After that http://localhost:8000 should be up and running

You can simply look at and run PHPUnit tests (look at tests folder where all test files are located) 
to execute all possible REST API endpoints (To run all tests execute this command from project's root folder: "php bin/phpunit"), but if you want, you can also use tools like POSTMAN 
to manually access REST API endpoints. Here is how to test all currently available API endpoints:
    
We can use POSTMAN to access all endpoints:
    
    1) Create API user to work with:
    
    method: POST
    url: http://localhost:8000/users/create
    Body (select raw) and add this line: {"email": "rest@jwtrestapi.com", "password": "test123"}
    
    you should get the following response on successful user creation:
    
    {
        "data": {
            "email": "rest@jwtrestapi.com"
        }
    }
    
    or 
    
    {
        "error": {
            "code": 400,
            "message": "User with given email already exists"
        }
    }
    
    if user with given email already exists.
    
    2) Authenticate (acquire JWT token) for just created user to be able to make REST API calls: 
    
    method: POST
    url: http://localhost:8000/api/authenticate
    Authorization type: Basic Auth
    username: rest@jwtrestapi.com
    password: test123
    
    {
        "data": {
            "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiJyZXN0QGp3dHJlc3RhcGkuY29tIiwiZW1haWwiOiJyZXN0QGp3dHJlc3RhcGkuY29tIiwiaWF0IjoxNTMwOTg1NTc2LCJleHAiOjE1MzA5ODkxNzZ9.CtGhP3YCs6Wz6o724ElU5-4GudcWpqYrBEDRRHrvjio"
        }
    }
    
    copy JWT token you got (without quotes)	to clipboard
    
    3) Use REST API using your JWT token
    
    - Create a football league:
    method: POST
    url: http://localhost:8000/api/leagues
    Body (select raw) and add this line: 
    
    {"name": "League 1"}
    
    Header:
    - Add header Key called "Authorization"
    - Add value: Bearer <your_jwt_token_value> (note there is space between "Bearer" and your JWT)
    
    Response should look similar to this:
    
    {
        "data": {
            "id": 181,
            "name": "League 1"
        }
    }
    
    - Create a football team request (use league id value you got from /api/leagues response):
    
    method: POST
    url: http://localhost:8000/api/teams
    Body (select raw) and add this line: 
    
    {"name": "Test team 1","strip": "Test strip 1","league_id": 181}
    
    Header:
    - Add header Key called "Authorization"
    - Add value: Bearer <your_jwt_token_value> (note there is space between "Bearer" and your JWT)	
    
    Response should look similar to this:
    
    {
        "data": {
            "id": 121,
            "name": "Test team 1",
            "strip": "Test strip 1",
            "league_id": 181
        }
    }
    
    - Update attributes of a football team. Let's say we want to change "strip" value of some particular football team:
    
    method: PUT
    url: http://localhost:8000/api/teams/{id} (where {id} is id of existing football team you want to modify, for example http://localhost:8000/api/teams/121)
    Body (select raw) and add this line: 
    {"strip": "New strip 1"}
    
    Header:
    - Add header Key called "Authorization"
    - Add value: Bearer <your_jwt_token_value> (note there is space between "Bearer" and your JWT)	
    
    Response should look similar to this:
    
    {
        "data": {
            "id": 121,
            "name": "Test team 1",
            "strip": "New strip 1",
            "league_id": 181
        }
    }
    
    - Delete football team:
    
    method: DELETE
    url: http://localhost:8000/api/teams/{id} (where {id} is id of existing football team you want to delete, for example http://localhost:8000/api/teams/121)	
    
    Header:
    - Add header Key called "Authorization"
    - Add value: Bearer <your_jwt_token_value> (note there is space between "Bearer" and your JWT)	
    
    Response HTTP status should be 204 (endpoint is successfully executed, but there is nothing to return)
    
    - Delete league:
    * Make sure all assigned football teams to this league are deleted (use "Delete football team" scenario to delete league's football teams)
    
    method: DELETE
    url: http://localhost:8000/api/leagues/{id} (where {id} is id of existing football team you want to delete, for example http://localhost:8000/api/leagues/181)	
    
    Header:
    - Add header Key called "Authorization"
    - Add value: Bearer <your_jwt_token_value> (note there is space between "Bearer" and your JWT)	
    
    Response HTTP status should be 204 (endpoint is successfully executed, but there is nothing to return)       

## To improve this REST API you can implement:
- pagination
- customize App\EventSubscriber to also support debug mode during development (to debug status 500 etc.) 
 (currently you need to manually go to processException() and just use "return;" on the first line of this method's body to avoid exception "prettyfying")
- SSL (https connection)
- there are many strings returned from services in case of various errors (see try/catch cases in FootballTeamService.php for example). It will be probably better to convert these to exceptions instead.
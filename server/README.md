# BursztynCRM API


### Technical details / Requirements:
- Current project is built using Symfony 4.4 framework
- It is based on microservice/API symfony project (symfony/skeleton)
	- https://symfony.com/download
- PHPUnit;	
- PHP 7.3;
- MariaDB;
- Composer 1.10;

### Installation:
    
    - go to project directory and run: composer install
    
    * at this point make sure MySQL is installed and is running	
    - open .env filde in project directory (copy .env.dist and create .env file out of it (in same location) if not exists)
    
    - configure DATATABSE_URL
        - This is example of how my .env config entry looks: DATABASE_URL=mysql://root:password@127.0.0.1:3306/bursztyn-crm 
    - copy and fill from api.copy to api.yaml, jst.copy to jst.yaml and smtp.copy to smtp.yaml in config directory;
    - go to project directory and run following commands to create database using Doctrine:
        - php bin/console doctrine:database:create (to create database called `bursztyn-crm`, it will figure out db name based on your DATABASE_URL config)		
        - php bin/console doctrine:schema:update --force (executes queries to create/update all Entities in the database in accordance to latest code)


## Usage/testing:


#### Import SQL query to create a test user. 

````

mysql -u username -p database_name < users.sql

````

  
##### Import your reservations and cottages from Idosell API. 


```
php bin/console app:synchronize-api-data
```

#### We can use POSTMAN to test all endpoints:


    Authenticate (acquire JWT token) for just created user to be able to make REST API calls: 
    
    method: POST
    url: http://localhost:8000/api/authenticate
    Authorization type: Basic Auth
    username: test@test.pl
    password: Test123
    
    {
        "data": {
            "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiJyZXN0QGp3dHJlc3RhcGkuY29tIiwiZW1haWwiOiJyZXN0QGp3dHJlc3RhcGkuY29tIiwiaWF0IjoxNTMwOTg1NTc2LCJleHAiOjE1MzA5ODkxNzZ9.CtGhP3YCs6Wz6o724ElU5-4GudcWpqYrBEDRRHrvjio"
        }
    }
    
    copy JWT token you got (without quotes)	to clipboard
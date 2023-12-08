# Aaxis Test (Api) 

Command cheat sheet to build the development environment del api rest Aaxis.

  - Clone repository
  - Install Libraries requirements
  - Config env variables
  - Create database
  - Start api server
  - Start web client
  - Test the App
  - Import Postman Collections from project folder

### Installations
First, it is necessary to install the [symfony Cli], depending on the operating system, you must execute the following commands:

Install on Linux.
```sh
$ wget https://get.symfony.com/cli/installer -O - | bash
```

Install on Mac.
```sh
$ curl -sS https://get.symfony.com/cli/installer | bash
```

Install on Windows.
Download the [symfony cli] executable from the symfony official page

##### Install Composer
 We will need a composer, which you can download and install from this page/website:
```sh
$ https://getcomposer.org/download/
```

### Clone repository
To clone the project you must execute the following command:
```sh
$ git clone https://github.com/arausseop/aaxis-test-project.git
```
### Install Libraries Requirements
Run the following composer command to install the vendors:
```sh
$ coposer install 
```
### Config env variables
Environment variables must be configured in order to initialize the database and load data fixtures (.env file):

Edit the `.env` file in the project root folder
  - Find and edit the following line with the database connection parameters\
  
`Database`
```
###> doctrine/doctrine-bundle ###
DATABASE_URL="postgresql://postgres:changeMe@127.0.0.1:5432/aaxis_test?serverVersion=15&charset=utf8"
###< doctrine/doctrine-bundle ###
```

`Create database`

#### Execute the commands to create database

##### Create Database:
To create the database configured with symfony commands, run the followings commands
```
$ symfony console doctrine:database:create
$ symfony console doctrine:migrations:migrate
```

`Load Data Fixtures`

#### Execute the commands to create load data fixtures
``` 
$ symfony console hautelook:fixtures:load 
```

`lexik/jwt-authentication-bundle`

#### Execute the commands to create public and private keys used by the lexik package
``` 
$ symfony console lexik:jwt:generate-keypair 
```

### Start Symfony Api Server
Install server certificates to enabled SSL protocole
``` 
$ server:ca:install 
```  
To remove the certificates run the following command

``` 
$ server:ca:uninstall
```  

To test that everything has gone well, symfony provides us with a command to start the application in a development environment and it is `symfony server:start`, once the server is started you can access the url` https://127.0.0.1:8000/ `and see the default Symfony home page.
All routes or endpoints created in the application will be requested through this base route, an example would be `https://127.0.0.1:8000/api/products`

### Import Postman Collections from project folder

For manual testing of different endpoints, a postman collection is provided ready to be imported and perform relevant tests on all endpoints. The file is located within the ``postman-collection`` folder within the project 

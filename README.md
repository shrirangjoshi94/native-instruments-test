# Native Instruments Test

## Prerequisite

- The below are required to be installed on the system for running this application.

 ```
    docker
    docker-compose
  ```
## Note

- Kindly stop Apache/Nginx server on your machine, because this project will run on port:80, for this web-server(Apache/Nginx) should be stopped on your machine to run application on domain without port.
- Also stop the mysql service.

## How To Set Up

- To set-up the project clone the project from the below link and then switch to development branch after navigating inside the project.

  ```
  git clone https://github.com/shrirangjoshi94/native-instruments-test.git
  
  git checkout development
  ```

- Create .env file using the below command.

  ```
  cp .env.example .env
  ```
  
- Execute the below command to start the docker.

  ```
  docker-compose up -d
  ```

- Install composer dependencies.

  ```
  docker exec -it app composer install
  ```
  
- Generate app key.

  ```
  docker exec -it app php artisan key:generate
  ```

- Migrate database tables.

  ```
  docker exec -it app php artisan migrate
  ```

- Command to seed initial data.

  ```
  docker exec -it app php artisan import:data
  ```

- Run test cases.

  ```
  docker exec -it app php artisan test
  ```

- To access mysql database.

  ```
  mysql -h 127.0.0.1 -u test -p
  
  User the password from DB_PASSWORD key in .env file
  ```

- The API's can be accessed at endpoints.

  ```
  POST localhost/api/auth
  GET localhost:8000/api/products
  GET localhost:8000/api/user
  GET localhost:8000/api/user/products
  DELETE localhost:8000/api/user/products/{sku}
  POST localhost:8000/api/user/products
  ```

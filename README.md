If you are using docker, you can do following steps in the root of project:
- ```docker build . --tag test```
- ```docker run test:latest --name=test_app```
- ```docker exec -it test_app bash```

Do following steps in the root of project in order:

- ```cp .env.example .env```
- ```composer install```
- ```php artisan key:generate```
- ```php artisan commission:calculate {file-path}```

For running test, run following script in the root of project:
- ```./vendor/bin/phpunit```

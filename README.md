## Getting started

This project was developed with Laravel 8 and powered by the development environment offered by Laravel Sail (Docker environment).

Main technologies used here:
* PHP 8
* MySQL 8 
* Laravel 8
* Laravel Passport
* JWT API Authentication
* Laravel Sail
* Docker / Docker-compose for development environment
* PHPUnit
* Laravel mix 
* React SPA front-end

It can be launched following some steps:

1. First you need to clone from [GitHub](https://github.com/rafaelrocha007/meveto-test)

```bash
git clone git@github.com:rafaelrocha007/meveto-test.git
```

or unpack the project code into a local folder.

2. Get into your folder:

```bash
cd meveto-test
```

or another folder name if you changed the destination folder on clone or unpacking.

3. Run composer install to get dependencies ready to use.

```bash
composer install
```

4. Make a `.env` copy from `.env.example` in root folder of project and fill with your own data. Let's take a look in
   some variables you must care about:

* If you are running a webserver like Apache or Nginx on your local machine, `APP_PORT` must be set to a different port
  from default (:80),

```dotenv
APP_PORT=8007
```

* Running the project based on `Sail` you should use `MySQL` and db user as `root`, db name and password does not matter
  at all.

```dotenv
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=meveto_test
DB_USERNAME=root
DB_PASSWORD=
```

* If you do not fill the following Laravel Sail variables, it will try to use default port values and may conflict with
  running applications running on host machine, like MySQL using `3306` or Redis using `6379`.

```dotenv
# Laravel Sail
FORWARD_DB_PORT=13306
FORWARD_REDIS_PORT=16379
```

* To work correct, is important provide also a Google Geocoding API key.

```dotenv
GOOGLE_MAPS_API_KEY=
```

5. Once variables were set, you should run `Laravel Sail` startup command:

```bash
vendor/bin/sail up
```

6. Now you can access your browser and see the Laravel main page through <http://localhost:8007>, see that `8007` port
   must be the same set on `APP_PORT` variable.
   
### Considerations
#### Importing data
Even customers importing runs on startup, you can run it again anytime running:
* If you start your app using Laravel Sail run:
```bash
vendor/bin/sail artisan import:customers
```
* Otherwise just run using php:
```bash
php artisan import:customers
```

#### Testing
Before running tests, make sure your `.env.testing` file in root folder contains at least this two variables set:
```dotenv
APP_ENV=testing
APP_KEY=sample_key
```
* If you start your app using Laravel Sail run:
```bash
vendor/bin/sail artisan test
```
* Otherwise just run using php:
```bash
php artisan test
```

## The application

This is a simple API with 4 endpoints:

(For convenience, this project has an [Insomnia](https://insomnia.rest/) collection attached at `storage/docs/InsomniaAPICollection.json`)

### Register
Register a new user
```json
POST /api/register
{
    "name": "Rafael",
    "email": "rafael.rocha.mg@gmail.com",
    "password": "12345678",
    "password_confirmation": "12345678"
}
```
#### Example Response
```json
{
  "token": "xxxxx.yyyyy.zzzzz"
}
```

### Login
Login to get access token for the next requests authentication
```json
POST /api/login
{
    "email": "rafael.rocha.mg@gmail.com",
    "password": "12345678",
}
```
#### Example Response
```json
{
  "token": "xxxxx.yyyyy.zzzzz"
}
```

### Customers | Retrieve all
Get a paginated collection of Customers, pass `page` parameter to get another page. You should also provide your access token as Bearer Authentication.
```json
GET /api/customers
```
#### Example Response
```json
{
    "data": [
        {
            "id": 1,
            "first_name": "Laura",
            "last_name": "Richards",
            "full_name": "Laura Richards",
            "gender": "Female",
            "city": "Warner",
            "state": "NH",
            "company": "Meezzy",
            "title": "Biostatistician III",
            "latitude": 43.2556568,
            "longitude": -71.8334145
        },
       ...
    ],
    "links": {
        "first": "http:\/\/localhost:8007\/api\/customers?page=1",
        "last": "http:\/\/localhost:8007\/api\/customers?page=40",
        "prev": null,
        "next": "http:\/\/localhost:8007\/api\/customers?page=2"
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 40,
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "active": false
            },
            {
                "url": "http:\/\/localhost:8007\/api\/customers?page=1",
                "label": "1",
                "active": true
            },
            {
                "url": "http:\/\/localhost:8007\/api\/customers?page=2",
                "label": "Next &raquo;",
                "active": false
            }
        ],
        "path": "http:\/\/localhost:8007\/api\/customers",
        "per_page": 25,
        "to": 25,
        "total": 1000
    }
}
```

### Customers | Retrieve one by `id`
Get a single Customer, passing `id` as parameter. You should also provide your access token as Bearer Authentication.
```json
GET /api/customers/{id}
```
#### Example Response
```json
{
    "id": 1,
    "first_name": "Laura",
    "last_name": "Richards",
    "full_name": "Laura Richards",
    "gender": "Female",
    "city": "Warner",
    "state": "NH",
    "company": "Meezzy",
    "title": "Biostatistician III",
    "latitude": 43.2556568,
    "longitude": -71.8334145
} 
```

## Screenshots

Login Form

![Login form](https://github.com/rafaelrocha007/meveto-test/blob/main/storage/docs/Customers-login-page.png?raw=true)  

Customers list page

![Login form](https://github.com/rafaelrocha007/meveto-test/blob/main/storage/docs/Customers-list-page.png?raw=true)  


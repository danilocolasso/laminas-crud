[![Docker](https://img.shields.io/badge/Docker-2496ED?logo=docker&logoColor=white)](https://www.docker.com/)
[![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?logo=php&logoColor=white)](https://www.php.net/releases/8.2/en.php)
[![Laravel](https://img.shields.io/badge/Laminas-10-013755?logo=zend&logoColor=white)](https://laravel.com/docs/10.x)

# Laminas CRUD
A simple CRUD test case using Laminas framework.

---

### Before install
Make sure you have installed Docker Desktop. If you don't, follow the <a href="https://www.docker.com/get-started" target="_blank">Get Started with Docker</a>.


### Installation guide

#### Clone the project
    git clone git@github.com:danilocolasso/laminas-crud.git

#### Enter project folder
    cd laminas-crud

#### Install the project
    make install

**All done!** Everything should work with a single command. \
The application will be available at
http://localhost:8080/


### Available commands
#### Install project
<sup>You only need to install once. After that you can just start it.</sup>

    make install

#### Start application
    make start

#### Restart application
    make restart

#### Stop application
    make stop

#### Application status
    make status

#### Enter app container bash
    make bash

#### Run tests
    make test

#### Clear application cache
    make cache-clear

#### Application dump composer autoload
    make dump-autoload

#### Delete application database folder
    make database-delete

For "make" command details, refer to the `makefile` in the application root.

### TO DO
- Authentication
- Create more tests for failure cases
- Add pagination
- Add email notification

<br />
<h4 align="center">
    Made with ♡ by <a href="https://www.linkedin.com/in/danilocolasso/" target="_blank">Danilo Colasso</a>
</h4>

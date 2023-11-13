<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# MINNY
This project is part of 01418471(Software engineer) and 01418442(Webtechnology and service) courses

![GitHub](https://img.shields.io/badge/github-%23121011.svg?style=for-the-badge&logo=github&logoColor=white)
![Git](https://img.shields.io/badge/git-%23F05033.svg?style=for-the-badge&logo=git&logoColor=white)
![ubuntu](https://img.shields.io/badge/Ubuntu-E95420?style=for-the-badge&logo=ubuntu&logoColor=white)
![Stack Overflow](https://img.shields.io/badge/-Stackoverflow-FE7A16?style=for-the-badge&logo=stack-overflow&logoColor=white)
![vscode](https://img.shields.io/badge/VSCode-0078D4?style=for-the-badge&logo=visual%20studio%20code&logoColor=white)
![yarn](https://img.shields.io/badge/Yarn-2C8EBB?style=for-the-badge&logo=yarn&logoColor=white)
![mysql](https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white)


<h2 align="left">Languages and Tools:</h2>
<a href="https://git-scm.com/" target="_blank">
    <img src="https://www.vectorlogo.zone/logos/git-scm/git-scm-icon.svg" alt="git" width="60" height="60"/>
</a>
&nbsp;&nbsp;&nbsp;&nbsp;
<a href="https://laravel.com/" target="_blank">
    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/9/9a/Laravel.svg/75px-Laravel.svg.png?20190820171151"alt="nuxt" width="60" height="60">
</a>
&nbsp;&nbsp;&nbsp;&nbsp;
<a href="https://kafka.apache.org/intro" target="_blank">
    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/0/05/Apache_kafka.svg/231px-Apache_kafka.svg.png"alt="nuxt" width="50" height="60">
</a>
&nbsp;&nbsp;&nbsp;&nbsp;
<a href="https://www.figma.com/" target="_blank"> 
    <img src="https://www.vectorlogo.zone/logos/figma/figma-icon.svg" alt="figma" width="50" height="50"/>
</a>
&nbsp;&nbsp;&nbsp;&nbsp;
<a href="https://www.docker.com/" target="_blank">
    <img src="https://raw.githubusercontent.com/devicons/devicon/master/icons/docker/docker-original-wordmark.svg" alt="docker" width="50" height="50"/>
</a>
&nbsp;&nbsp;&nbsp;&nbsp;
<a href="https://www.gnu.org/software/bash/" target="_blank"> 
    <img src="https://www.vectorlogo.zone/logos/gnu_bash/gnu_bash-icon.svg" alt="bash" width="50" height="50"/> 
</a>

## Setup

Make sure to install the dependencies:

```bash
#open the docker desktop
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php82-composer:latest \
    composer install --ignore-platform-reqs

# next command
cp .env.example .env

# write in the .env file
APP_NAME="your project" (line 1)
DB_HOST=mysql (line 12)
DB_USERNAME=sail (line 15)
DB_PASSWORD=password (line 16)
REDIS_HOST=redis (line 27)

#add the vite.config.js
server:{
    hmr:{
        host: 'localhost',
    }
}

#sail
sail up -d
sail artisan key:generate
sail artisan storage:link
sail artisan migrate --seed

# yarn
sail yarn install
sail yarn dev

```

## Architecture project
```
.
├───app
│   ├───Http
│   │   └───Controllers
│   │       └───Api
│   ├───Infrastructure
│   │   ├───Domain
│   │   └───Kafka
│   └───Models
├───bootstrap
├───config
│   ├───app.php
│   └───kafka.php
├───database
│   ├───factories
│   ├───migrations
│   └───seeders
├───public
├───resources
├───routes
│   └───api.php
├───storage
│   └───app
│       └───public
│           └───products/images
├───tests
│   ├───Feature
│   └───Unit
├───vendor
├── vite.config.js
└── yarn.lock
```

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

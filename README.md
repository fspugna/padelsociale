# Padel Sociale

Padel Sociale is the web site of  "Giro padel tournament" 
Is mobile-ready writing in laravel and powered HTML5 Markdown editor.


### Installation via SSH

Padel Sociale requires [composer](https://getcomposer.org/) and  [Node.js](https://nodejs.org/) v4+ to run.

Install the dependencies and devDependencies and start the server.

```sh
git clone https://leleg@bitbucket.org/madeinapp/amatoripadel.git
composer update
cp .env.example .env  (fix db connection and mail setting in new file)
php artisan key:generate
composer dump-autoload

php artisan notifications:table
php artisan migrate:refresh --seed

import database/comuni-italiani.sql
php artisan storage:link
php artisan passport:install
php artisan config:cache
npm install

```

The setup of virtual host is:

```sh
<VirtualHost *:80>
    DocumentRoot "D:\{folderprojects}\amatoripadel\public"
    ServerName www.amatoripadel.it
    ServerAdmin webmaster@amatoripadel.it
    ErrorLog "logs/amatoripadel-error.log"
    CustomLog "logs//amatoripadel-access.log" common
    <Directory  "D:\{folderprojects}\amatoripadel\public">
        Options Indexes FollowSymLinks MultiViews
        AllowOverride All
        Require all granted
  </Directory>
</VirtualHost>
```
```


### Installation in hosting via FTP

```sh
Do a local installation of the project
copy all files via FTP/SFTP
```

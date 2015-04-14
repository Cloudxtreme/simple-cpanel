simple-cpanel

a tiny nginx vhost control panel built with Symfony2 + Doctrine ORM
=============

GUIDE (RUSSIAN):
----------------

Функционал приложения:
----------------
- Создание хоста через панель
- Генерация дефолтного nginx-конфига и дефолтного index.html по редактируемым шаблонам
- Редактирование имени хоста после создания без потери конфига или данных
- Просмотр веб-сайта по указанному пути

Принцип работы:
Всё просто. В случае удачного создания, редактирования или удаления, контроллер вызывает соответствующий метод сервиса VirtualHostHandler, который занимается I/O-операциями, а также содержит метод для перезагрузки nginx. Команду для горячей перезагрузки nginx и пути до директорий с сайтами и их конфигов nginx нужно указать в app/config/parameters.yml. Дефолтные шаблоны вебсайта и конфига можно редактировать в @FastVPSCpanelBundle/Resources/template.

Требования
---------------
- os linux (под windows не тестировалось)
- php 5.6
- nginx + php-fpm
- php5-cli

Установка:
--------------
- Загрузить все данные проекта в директорию (например, /home/www/website.com/simple-cpanel/)
- Указать в app/config/parameters.yml правильные директории для хостов и их конфигов (у меня это /home/www/dev.kolimaa.eu/simple-cpanel/web и /etc/nginx/cpanel.d/), создать их, изменить их права на 0777.
- Указать в app/config/parameters.yml команду для релоада nginx (запускающий приложение пользователь обязательно должен иметь на это права, либо сделать разрешение через /etc/sudoers); в том же файле настроить подключение к MySQL и указать созданную базу данных.
- Создать nginx-конфигурацию для приложения вроде этой (APPLICATION_PATH - путь до директории с приложением, VHOST_CONFIG_PATH - путь до директории с конфигурациями сайтов)

```
server {
    server_name %DOMAIN%;
    root %APPLICATION_PATH%/web;

    location / {
        # try to serve file directly, fallback to app.php
        try_files $uri /app.php$is_args$args;
        index app.php;
    }
    # PROD
    location ~ ^/app\.php(/|$) {
        fastcgi_pass unix:/var/run/php5-fpm.sock;
        fastcgi_split_path_info ^(.+\.php)(/.*)$;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_param HTTPS off;
        internal;
    }

    include %VHOST_CONFIG_PATH%/*.conf;

    error_log /var/log/nginx/dev_error.log;
    access_log /var/log/nginx/dev_access.log;
}
```

В данном случае nginx будет подключать locations, которые создает приложение. По умолчанию так и задумано.

- Выполнить следущую последовательность команд:

```
cd %APPLICATION_PATH%
php app/console assetic:dump
php app/console doctrine:schema:update --force
php app/console cache:clear --env=prod
```

- Готово!
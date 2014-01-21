ОПИСАНИЕ:
=======================

Создание системы управления контентом, который должен представлять собой иерархическое дерево ресурсов. Ресурсами могут быть как загружаемые со стороны клиента файлы (архивы, pdf, png и т.д.), так и страницы, создаваемые и редактируемые из системы, в окне браузера (WYSIWYG editor).




УСТАНОВКА:
=======================

Установить composer:

1. curl -sS https://getcomposer.org/installer | php
2. mv composer.phar /usr/local/bin/composer

Дамп базы находится в папке INSTALL. Там же вы найдёте и файл диаграм MySql WorkBench.

После установки composqer клонировать проект, затем зайти в папку с проектом и выполнить:

sudo composer install

После выполнения комманды в файле app/config/parameters.yml указать параметры подключения к БД

Далее следует дать права на запись папкам app/cache, app/logs

В браузере открыть фал web/app_dev.php либо web/app.php

SOLVED


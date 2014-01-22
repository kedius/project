ОПИСАНИЕ:
=======================

Создание системы управления контентом, который должен представлять собой иерархическое дерево ресурсов. Ресурсами могут быть как загружаемые со стороны клиента файлы (архивы, pdf, png и т.д.), так и страницы, создаваемые и редактируемые из системы, в окне браузера (WYSIWYG editor).




УСТАНОВКА:
=======================

Установить composer:

<pre>
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
</pre>


После установки composqer клонировать проект, затем зайти в папку с проектом и установить зависимости:
<pre>
git clone https://github.com/kedius/project.git (скорее всего потребует sudo, тогда воспользуйтесь sudo !!)
cd project
sudo composer install
</pre>

Дамп базы находится в папке INSTALL. Там же вы найдёте и файл диаграм MySql WorkBench.

После выполнения комманды в файле app/config/parameters.yml указать параметры подключения к БД

Далее следует дать права на запись папкам app/cache, app/logs и web/uploads

В браузере открыть фал web/app_dev.php либо web/app.php


ПОЛЬЗОВАТЕЛИ:
=======================

<pre>
Пользователь:
  login:    user
  password: user
Автор:
  login:    author
  password: author
Издатель:
  login:    publisher
  password: publisher
Администратор:
  login:    admin
  password: admin
</pre>


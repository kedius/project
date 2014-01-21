-- phpMyAdmin SQL Dump
-- version 4.0.6deb1
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Янв 21 2014 г., 17:26
-- Версия сервера: 5.5.34-0ubuntu0.13.10.1
-- Версия PHP: 5.5.3-1ubuntu2.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `project`
--

-- --------------------------------------------------------

--
-- Структура таблицы `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `alias` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `lft` int(11) NOT NULL,
  `lvl` int(11) NOT NULL,
  `rgt` int(11) NOT NULL,
  `root` int(11) DEFAULT NULL,
  `parent` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `fk_categories_categories1_idx` (`parent`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=25 ;

--
-- Дамп данных таблицы `categories`
--

INSERT INTO `categories` (`id`, `title`, `alias`, `lft`, `lvl`, `rgt`, `root`, `parent`) VALUES
(20, 'рутовая категория', 'rutovaya_kategoriya', 1, 0, 6, 20, NULL),
(21, 'подкатегория первого уровня', 'podkategoriya_pervogo_urovnya', 2, 1, 5, 20, 20),
(22, 'ещё 1 уровень вложенности', 'esch_1_uroven_vlojennosti', 3, 2, 4, 20, 21),
(23, 'iTransition', 'iTransition', 1, 0, 4, 23, NULL),
(24, 'Информация', 'informatsiya', 2, 1, 3, 23, 23);

-- --------------------------------------------------------

--
-- Структура таблицы `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `content_id` int(10) unsigned NOT NULL,
  `created_at` datetime NOT NULL,
  `message` text COLLATE utf8_unicode_ci NOT NULL,
  `rating` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `fk_comments_user1_idx` (`user_id`),
  KEY `fk_comments_articles1_idx` (`content_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=18 ;

--
-- Дамп данных таблицы `comments`
--

INSERT INTO `comments` (`id`, `user_id`, `content_id`, `created_at`, `message`, `rating`) VALUES
(17, 16, 32, '2014-01-21 17:25:28', 'Зарегистрировался и теперь могу оставлять комментарии!', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `content`
--

CREATE TABLE IF NOT EXISTS `content` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `content_type` int(10) unsigned NOT NULL,
  `author` int(10) unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `alias` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `text` longtext COLLATE utf8_unicode_ci NOT NULL,
  `date_at` datetime NOT NULL,
  `category` int(10) unsigned NOT NULL,
  `status` int(11) NOT NULL,
  `publisher` int(10) unsigned DEFAULT NULL,
  `public_date` datetime DEFAULT NULL,
  `rating` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `fk_articles_user1_idx` (`author`),
  KEY `fk_articles_statuses1_idx` (`status`),
  KEY `fk_articles_categories1_idx` (`category`),
  KEY `fk_articles_user2_idx` (`publisher`),
  KEY `fk_content_content_types1_idx` (`content_type`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=33 ;

--
-- Дамп данных таблицы `content`
--

INSERT INTO `content` (`id`, `content_type`, `author`, `title`, `alias`, `text`, `date_at`, `category`, `status`, `publisher`, `public_date`, `rating`) VALUES
(32, 2, 14, 'Компания iTransition', 'kompaniya_iTransition', '<h1>Компания</h1><div><div><p>Itransition является одним из ведущих разработчиков программного обеспечения в СНГ. Мы предоставляем услуги полного цикла по разработке, внедрению и сопровождению программных решений клиентам из более чем 30-ти стран мира.</p></div><h2>Краткий обзор</h2><div><table width="100%" cellspacing="0" cellpadding="0"><tbody><tr><td width="200"><p>Год основания</p></td><td width="300" colspan="4"><p>1998</p></td></tr><tr><td><p>Количество сотрудников</p></td><td colspan="4"><p>1100+</p></td></tr><tr><td><p>Основные офисы продаж</p></td><td colspan="4"><ul><li>Москва, Росcия</li><li>Остин, США</li><li>Лондон, Великобритания</li></ul></td></tr><tr><td><p>Основной центр разработки</p></td><td colspan="4"><p>Минск, Беларусь</p></td></tr><tr><td><p>Основные сервисы<br></p></td><td colspan="4"><ul><li>Разработка заказного программного обеспечения</li><li>Аутсорсинг разработки программных продуктов</li><li>Низкоуровневое программирование</li><li>Системная интеграция</li><li>Разработка и внедрение корпоративных информационных систем, включая SAP-решения</li><li>Разработка решений в области электронной коммерции</li><li>Разработка онлайн-сервисов, порталов, интернет-сайтов</li><li>Независимый контроль качества ПО</li></ul></td></tr><tr><td><p>Отрасли</p><br></td><td colspan="4"><ul><li>Нефтегазовая отрасль<br></li><li>Телекоммуникации<br></li><li>Промышленное производство<br></li><li>Строительство и недвижимость<br></li><li>Торговля</li><li>Интеллектуальные услуги</li><li>Интернет-бизнес</li><li>Электронные платежи</li></ul></td></tr><tr><td><p>Бизнес-домены</p><br></td><td colspan="4"><ul><li>Управление бизнес-процессами</li><li>Управление контентом и цифровыми активами</li><li>Электронный документооборот</li><li>Управление взаимоотношениями с клиентами</li><li>Управленческая аналитика</li><li>Ситуационно-аналитические системы<br></li></ul></td></tr></tbody></table></div><p>Более подробную информацию вы всегда можете получить&nbsp;<a href="http://www.itransition.by/contacts/">связавшись с нами</a>.</p></div>', '2014-01-21 17:03:58', 24, 3, 13, '2014-01-21 17:06:40', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `content_review`
--

CREATE TABLE IF NOT EXISTS `content_review` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `content_id` int(10) unsigned NOT NULL,
  `comment` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `fk_content_review_content1_idx` (`content_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=21 ;

--
-- Дамп данных таблицы `content_review`
--

INSERT INTO `content_review` (`id`, `content_id`, `comment`) VALUES
(20, 32, 'Белоруссия переживает бум информационных технологий, поэтому здесь постоянно необходимы кадры в сфере IT. Крупнейший работодатель в Белоруссии — это Itransition.');

-- --------------------------------------------------------

--
-- Структура таблицы `content_to_tags`
--

CREATE TABLE IF NOT EXISTS `content_to_tags` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `content_id` int(10) unsigned NOT NULL,
  `tags_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `fk_content_to_tags_content1_idx` (`content_id`),
  KEY `fk_content_to_tags_tags1_idx` (`tags_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=14 ;

--
-- Дамп данных таблицы `content_to_tags`
--

INSERT INTO `content_to_tags` (`id`, `content_id`, `tags_id`) VALUES
(10, 32, 28),
(11, 32, 29),
(12, 32, 30),
(13, 32, 31);

-- --------------------------------------------------------

--
-- Структура таблицы `content_types`
--

CREATE TABLE IF NOT EXISTS `content_types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `content_types`
--

INSERT INTO `content_types` (`id`, `type`) VALUES
(1, 'Статья'),
(2, 'Файл');

-- --------------------------------------------------------

--
-- Структура таблицы `files`
--

CREATE TABLE IF NOT EXISTS `files` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `content_id` int(10) unsigned NOT NULL,
  `file_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `file_path` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `mime_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `fk_files_content1_idx` (`content_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=27 ;

--
-- Дамп данных таблицы `files`
--

INSERT INTO `files` (`id`, `content_id`, `file_name`, `file_path`, `mime_type`) VALUES
(26, 32, 'Философия бизнеса.pdf', 'uploads/author/content/21-01-2014_17-03-58', 'application/pdf');

-- --------------------------------------------------------

--
-- Структура таблицы `statuses`
--

CREATE TABLE IF NOT EXISTS `statuses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Дамп данных таблицы `statuses`
--

INSERT INTO `statuses` (`id`, `status`) VALUES
(1, 'Не опубликована'),
(2, 'Отказано в публикации'),
(3, 'Опубликована');

-- --------------------------------------------------------

--
-- Структура таблицы `tags`
--

CREATE TABLE IF NOT EXISTS `tags` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=32 ;

--
-- Дамп данных таблицы `tags`
--

INSERT INTO `tags` (`id`, `name`) VALUES
(28, 'itransition'),
(29, 'компания'),
(30, 'информация'),
(31, 'бизнес');

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `salt` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `login_UNIQUE` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=17 ;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `salt`, `image`) VALUES
(5, 'kedius', 'CXJxsXy51/I+uUmZvFE5j5BUcgPHLxi85sYJltyDv5Zuv9HpY2ASVhGVSoDso1BbFCXSUdUiwztsHdvIN7CF3A==', 'cbed3dccc02c3c93174b517218af8259', 'uploads/kedius/avatar/d3ecd9ac60db91477b7b320dedbe24be.jpeg'),
(13, 'publisher', 'tGCMS4XWnjrBl2GaeheoNrCjq6cM7vqQ7tDact/Fsbpbsk7NMB4w7NzYvPgRxmnd4WDa0rhxCHBmeBboOPp5Ag==', '1645c027bf0e4eedff6fd0acc3f1cb92', 'uploads/publisher/avatar/0d7acbe3bb5677553599d6ccc62cc591.jpeg'),
(14, 'author', 'P+mzerFglKUiKHqphg6xmIW5QgYTp4vVpXArPJ1M5UBL6o+BZB+4iBKVOR2VC+ohxiWGlnwTxRG4+Fq+2i4I1g==', '1dbe1e07cacc910f9b222548b95dd3ac', 'uploads/author/avatar/d6efc63b26de18c0a85083a8bb8b2ec0.jpeg'),
(15, 'admin', 'BuFPRbFYtBc1uDg04D7Tu51nP8rGlIi4NNB8fxarAazgJ53fpHXT9jNFI/GmZxKfWj5HOie7g8H3TL0k+zSO8g==', '3274d9a66cb6d5f0609462ea28bd8354', NULL),
(16, 'user', 'UFcUs6cLjTxjKc2ojSBXc5Qa9otJvnuYiTX960dqRP3bg89eyaFS/jC+Yu1/JU3Jn2y6E+79i8z3kZ0eKUcVtg==', 'd51ec2e15d1d5c6d4d3d534edb0f83f4', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `user_roles`
--

CREATE TABLE IF NOT EXISTS `user_roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Дамп данных таблицы `user_roles`
--

INSERT INTO `user_roles` (`id`, `role`) VALUES
(1, 'ROLE_USER'),
(2, 'ROLE_AUTHOR'),
(3, 'ROLE_PUBLISHER'),
(4, 'ROLE_ADMIN');

-- --------------------------------------------------------

--
-- Структура таблицы `user_to_roles`
--

CREATE TABLE IF NOT EXISTS `user_to_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `user_roles_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_user_to_roles_user_idx` (`user_id`),
  KEY `fk_user_to_roles_user_roles1_idx` (`user_roles_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=78 ;

--
-- Дамп данных таблицы `user_to_roles`
--

INSERT INTO `user_to_roles` (`id`, `user_id`, `user_roles_id`) VALUES
(63, 13, 1),
(64, 13, 3),
(66, 14, 1),
(67, 14, 2),
(70, 5, 1),
(71, 5, 4),
(72, 5, 2),
(73, 5, 3),
(75, 15, 1),
(76, 15, 4),
(77, 16, 1);

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `fk_categories_categories1` FOREIGN KEY (`parent`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `fk_comments_user1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_comments_articles1` FOREIGN KEY (`content_id`) REFERENCES `content` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `content`
--
ALTER TABLE `content`
  ADD CONSTRAINT `fk_content_categories1` FOREIGN KEY (`category`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_content_content_types1` FOREIGN KEY (`content_type`) REFERENCES `content_types` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_content_statuses1` FOREIGN KEY (`status`) REFERENCES `statuses` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_content_user1` FOREIGN KEY (`author`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_content_user2` FOREIGN KEY (`publisher`) REFERENCES `user` (`id`) ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `content_review`
--
ALTER TABLE `content_review`
  ADD CONSTRAINT `fk_content_review_content1` FOREIGN KEY (`content_id`) REFERENCES `content` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `content_to_tags`
--
ALTER TABLE `content_to_tags`
  ADD CONSTRAINT `fk_content_to_tags_content1` FOREIGN KEY (`content_id`) REFERENCES `content` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_content_to_tags_tags1` FOREIGN KEY (`tags_id`) REFERENCES `tags` (`id`) ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `files`
--
ALTER TABLE `files`
  ADD CONSTRAINT `fk_files_content1` FOREIGN KEY (`content_id`) REFERENCES `content` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `user_to_roles`
--
ALTER TABLE `user_to_roles`
  ADD CONSTRAINT `fk_user_to_roles_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user_to_roles_user_roles1` FOREIGN KEY (`user_roles_id`) REFERENCES `user_roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 4.6.4deb1
-- https://www.phpmyadmin.net/
--
-- Хост: localhost:3306
-- Время создания: Ноя 03 2018 г., 11:23
-- Версия сервера: 5.7.18-0ubuntu0.16.10.1-log
-- Версия PHP: 7.1.23-3+ubuntu16.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `kaanima`
--

-- --------------------------------------------------------

--
-- Структура таблицы `courses`
--

CREATE TABLE `courses` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text,
  `type` tinyint(1) UNSIGNED NOT NULL,
  `img` int(11) UNSIGNED NOT NULL,
  `price_month` decimal(13,2) UNSIGNED NOT NULL,
  `price_full` decimal(13,2) UNSIGNED NOT NULL,
  `author` int(11) UNSIGNED NOT NULL,
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `active` tinyint(1) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `courses`
--

INSERT INTO `courses` (`id`, `name`, `description`, `type`, `img`, `price_month`, `price_full`, `author`, `ts`, `active`) VALUES
(1, ' 3ds Max Базовое моделирование', 'Курс 3ds Max Базовое моделирование — это первая ступень в изучении самого популярного редактора трехмерной графики 3ds Max. Рекомедуем начинать именно с этого модуля, потому что в нём чётко и системно изложен основополагающий материал.', 0, 1, '12.00', '120.00', 12, '2018-07-15 08:42:54', 1),
(2, 'Визуализация в 3ds Max', 'Курс Визуализация в 3ds Max является логическим продолжением курса Базового моделирования (модуль 201). В этом курсе слушатели научатся создавать материалы и выполнять светопостановку в сценах 3ds Max используя физические материалы и фотометрические источники света, а так же визуализировать проекты с использованием рендеров 3ds Max Scanline и ART (Autodesk Raytracer). Курс необходим начинающим ', 1, 0, '50.00', '350.00', 12, '2018-07-15 08:48:29', 1),
(3, '3ds Max + Corona Интерьер', 'Курс 3ds Max интерьерное 3d-моделирование — это практикум по созданию интерьера. От слушателей требуется знание всех тем базового курса: 201. Базового моделирования и 202. Визуализации в 3ds Max. В любом проекте интерьера необходимо уметь работать с большими набором объектов, создавать сложные модели, уметь оптимизировать и настраивать рабочий процесс. ', 0, 0, '98.00', '392.00', 12, '2018-07-16 19:56:16', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `courses_groups`
--

CREATE TABLE `courses_groups` (
  `id` int(11) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `course_id` int(11) UNSIGNED NOT NULL,
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `ts_end` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `courses_groups`
--

INSERT INTO `courses_groups` (`id`, `code`, `course_id`, `ts`, `ts_end`) VALUES
(1, 'c1-201812', 1, '2018-09-30 21:00:00', '2019-01-13 21:00:00'),
(2, 'c2-201812', 2, '2018-11-30 21:00:00', '2018-12-16 21:00:00');

-- --------------------------------------------------------

--
-- Структура таблицы `files`
--

CREATE TABLE `files` (
  `id` int(11) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_type` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `full_path` varchar(255) NOT NULL,
  `raw_name` varchar(255) NOT NULL,
  `orig_name` varchar(255) NOT NULL,
  `client_name` varchar(255) NOT NULL,
  `file_ext` varchar(20) NOT NULL,
  `file_size` float(6,2) UNSIGNED NOT NULL,
  `is_image` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `image_width` smallint(6) NOT NULL,
  `image_height` smallint(6) NOT NULL,
  `image_type` varchar(255) NOT NULL,
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `files`
--

INSERT INTO `files` (`id`, `file_name`, `file_type`, `file_path`, `full_path`, `raw_name`, `orig_name`, `client_name`, `file_ext`, `file_size`, `is_image`, `image_width`, `image_height`, `image_type`, `ts`) VALUES
(1, '38a1d033dad07ea488acd7f0912d1881.jpg', 'image/jpeg', 'data/courses/', 'data/courses/38a1d033dad07ea488acd7f0912d1881.jpg', '38a1d033dad07ea488acd7f0912d1881', '2993d4cd1e88430f097cc13cc524f9ab.jpg', '2993d4cd1e88430f097cc13cc524f9ab.jpg', '.jpg', 30.18, 1, 900, 506, 'jpeg', '2018-10-22 21:00:00'),
(2, '371401d33da2941fbba8cefa657409f7.png', 'image/png', 'data/', 'data/371401d33da2941fbba8cefa657409f7.png', '371401d33da2941fbba8cefa657409f7', 'dart.png', 'dart.png', '.png', 16.65, 1, 128, 128, 'png', '2018-10-22 21:00:00'),
(3, '0b9e90a15c8e7e8467dc4970a1884f3f.jpg', 'image/jpeg', 'data/', 'data/0b9e90a15c8e7e8467dc4970a1884f3f.jpg', '0b9e90a15c8e7e8467dc4970a1884f3f', 'e874c4f1c1ac9f48a9775044f74e679b.jpg', 'e874c4f1c1ac9f48a9775044f74e679b.jpg', '.jpg', 89.45, 1, 900, 506, 'jpeg', '2018-10-22 21:00:00');

-- --------------------------------------------------------

--
-- Структура таблицы `lectures`
--

CREATE TABLE `lectures` (
  `id` int(10) UNSIGNED NOT NULL,
  `active` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `course_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text,
  `task` text,
  `type` tinyint(1) UNSIGNED NOT NULL,
  `video` varchar(255) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modify` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `sort` smallint(5) UNSIGNED NOT NULL DEFAULT '500'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `lectures`
--

INSERT INTO `lectures` (`id`, `active`, `course_id`, `name`, `description`, `task`, `type`, `video`, `created`, `modify`, `sort`) VALUES
(1, 1, 1, 'Первая лекция 3D Max', 'Первая лекция описание', 'Описание задачи 01 - Первая лекция 3D Max', 0, 'https://www.youtube.com/embed/K3_dFLX83t4', '2018-07-15 11:54:06', '2018-07-15 11:54:06', 500),
(2, 1, 1, 'Вторая лекция 3D Max', 'Вторая лекция описание', '', 0, 'https://www.youtube.com/embed/oz-5KjJYsPU', '2018-07-15 11:54:48', '2018-07-15 11:54:48', 500),
(3, 1, 2, 'Лекция 1', 'Лекция 1', NULL, 0, 'https://www.youtube.com/embed/8X7ZOwpCC8I', '2018-08-01 18:20:54', '2018-08-01 18:20:54', 500),
(4, 1, 0, 'Бонусная лекция', 'Бонусная лекция описание', '', 1, 'https://www.youtube.com/watch?v=x8XXOvIEVco', '2018-07-15 11:54:48', '2018-07-15 11:54:48', 500),
(5, 1, 1, 'Третья лекция 3D Max', 'Третья лекция описание', '', 0, 'https://www.youtube.com/watch?v=HhgcOTeaobI', '2018-07-15 11:54:48', '2018-07-15 11:54:48', 500),
(6, 1, 0, 'Вступительная лекция', 'Вступительная лекция', NULL, 1, 'https://www.youtube.com/embed/oz-5KjJYsPU', '2018-07-15 11:54:48', '2018-07-15 11:54:48', 0),
(7, 1, 1, 'Четвертая лекция 3D Max', 'Четвертая лекция описание', '', 0, 'https://www.youtube.com/watch?v=HhgcOTeaobI', '2018-07-15 11:54:48', '2018-07-15 11:54:48', 500),
(8, 1, 1, 'Пятая лекция 3D Max', 'Пятая лекция описание', '', 0, 'https://www.youtube.com/watch?v=HhgcOTeaobI', '2018-07-15 11:54:48', '2018-07-15 11:54:48', 500);

-- --------------------------------------------------------

--
-- Структура таблицы `lectures_groups`
--

CREATE TABLE `lectures_groups` (
  `group_id` int(11) UNSIGNED NOT NULL,
  `lecture_id` int(11) UNSIGNED NOT NULL,
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `lectures_groups`
--

INSERT INTO `lectures_groups` (`group_id`, `lecture_id`, `ts`) VALUES
(1, 1, '2018-10-02 21:00:00'),
(1, 2, '2018-10-09 21:00:00'),
(1, 5, '2018-10-16 21:00:00'),
(1, 7, '2018-10-23 21:00:00'),
(1, 8, '2018-10-30 21:00:00'),
(2, 3, '2018-12-02 21:00:00');

-- --------------------------------------------------------

--
-- Структура таблицы `lectures_homework`
--

CREATE TABLE `lectures_homework` (
  `id` int(11) UNSIGNED NOT NULL,
  `group_id` int(11) UNSIGNED NOT NULL,
  `lecture_id` int(11) UNSIGNED NOT NULL,
  `user` int(11) UNSIGNED NOT NULL,
  `type` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `video_url` varchar(255) DEFAULT NULL,
  `file` int(11) UNSIGNED DEFAULT NULL,
  `comment` varchar(255) NOT NULL,
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `lectures_homework`
--

INSERT INTO `lectures_homework` (`id`, `group_id`, `lecture_id`, `user`, `type`, `video_url`, `file`, `comment`, `ts`) VALUES
(1, 1, 1, 12, 0, NULL, 2, '', '2018-10-23 19:42:30'),
(2, 1, 1, 12, 0, NULL, 3, 'test', '2018-10-23 19:42:48');

-- --------------------------------------------------------

--
-- Структура таблицы `lectures_status`
--

CREATE TABLE `lectures_status` (
  `lecture_id` int(11) UNSIGNED NOT NULL,
  `user` int(11) UNSIGNED NOT NULL,
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `migrations`
--

CREATE TABLE `migrations` (
  `version` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `migrations`
--

INSERT INTO `migrations` (`version`) VALUES
(20180625215650);

-- --------------------------------------------------------

--
-- Структура таблицы `review`
--

CREATE TABLE `review` (
  `id` int(11) UNSIGNED NOT NULL,
  `group_id` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `lecture_id` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `user` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `video_url` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `score` tinyint(3) UNSIGNED NOT NULL,
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `review`
--

INSERT INTO `review` (`id`, `group_id`, `lecture_id`, `user`, `video_url`, `text`, `score`, `ts`) VALUES
(1, 1, 1, 12, 'https://www.youtube.com/embed/2vcHcAWZBcs', 'Рекомендации (лат. recommendatio — совет) — в международном праве означают резолюции международных организаций, совещаний или конференций, которые не имеют обязательной юридической силы. Рекомендации не являются источниками международного права, однако они активно способствуют формированию новых норм и принципов международного права. В исключительных случаях рекомендации могут быть признаны юридически обязательными (например, рекомендации Гене­ральной Ассамблеи ООН в адрес ЭКОСОС, согласно ст.66 Устава ООН, имеют характер обязательных указаний)[1]. Для того чтобы рекомендация была признана обязательной для государства, необходимо волеизъявление такого государства. В виде рекомендаций часто принимаются Резолюции Генеральной Ассамблеи ООН и международных организаций системы ООН. Важными, по своему содержанию, являются рекомендации Совета Безопасности ООН относительно разрешения спора между государствами мирными средствами[2]. Рекомендации также могут приниматься региональными международными организациями.', 0, '2018-10-25 12:42:36'),
(2, 1, 1, 12, 'https://www.youtube.com/embed/2vcHcAWZBcs', 'Рекомендации Рекомендации Рекомендации Рекомендации', 0, '2018-10-25 12:42:36'),
(3, 1, 1, 12, 'https://www.youtube.com/embed/2vcHcAWZBcs', 'Рекомендации Рекомендации Рекомендации Рекомендации', 0, '2018-10-25 12:42:36'),
(4, 1, 1, 12, 'https://www.youtube.com/embed/2vcHcAWZBcs', 'Рекомендации Рекомендации Рекомендации Рекомендации', 0, '2018-10-25 12:42:36'),
(5, 1, 1, 12, 'https://www.youtube.com/embed/2vcHcAWZBcs', 'Рекомендации Рекомендации Рекомендации Рекомендации', 0, '2018-10-25 12:42:36'),
(6, 1, 2, 0, 'https://www.youtube.com/watch?v=aM-B7jZwX0s', 'Рекомендации 3ds Max Базовое моделирование Вторая лекция 3D Max', 0, '2018-10-26 10:05:14');

-- --------------------------------------------------------

--
-- Структура таблицы `streams`
--

CREATE TABLE `streams` (
  `id` int(11) UNSIGNED NOT NULL,
  `group_id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `url` varchar(255) NOT NULL,
  `ts` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `streams`
--

INSERT INTO `streams` (`id`, `group_id`, `name`, `description`, `url`, `ts`) VALUES
(1, 3, 'Хеллоуин', 'Хеллоуин Хеллоуин Хеллоуин', 'https://youtu.be/Hl6tP2y-Arg', '2018-11-03 20:59:00'),
(2, 2, 'Хеллоуин', 'Хеллоуин Хеллоуин Хеллоуин', 'https://youtu.be/Hl6tP2y-Arg', '2018-11-04 20:59:00'),
(3, 2, 'Хеллоуин', 'Хеллоуин Хеллоуин Хеллоуин', 'https://youtu.be/Hl6tP2y-Arg', '2018-11-06 20:59:00');

-- --------------------------------------------------------

--
-- Структура таблицы `subscription`
--

CREATE TABLE `subscription` (
  `id` int(11) UNSIGNED NOT NULL,
  `user` int(11) UNSIGNED NOT NULL,
  `type` tinyint(1) UNSIGNED NOT NULL,
  `service` int(11) UNSIGNED NOT NULL,
  `description` text NOT NULL,
  `ts_start` timestamp NULL DEFAULT NULL,
  `ts_end` timestamp NULL DEFAULT NULL,
  `subscr_type` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `amount` decimal(13,2) UNSIGNED NOT NULL DEFAULT '0.00',
  `price_month` decimal(13,2) UNSIGNED NOT NULL,
  `price_full` decimal(13,2) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `subscription`
--

INSERT INTO `subscription` (`id`, `user`, `type`, `service`, `description`, `ts_start`, `ts_end`, `subscr_type`, `amount`, `price_month`, `price_full`) VALUES
(1, 12, 0, 1, ' 3ds Max Базовое моделирование (Декабрь 2018)', '2018-09-30 21:00:00', '2019-01-13 21:00:00', 0, '0.00', '12.00', '120.00'),
(2, 12, 0, 2, 'Визуализация в 3ds Max (Декабрь 2018)', '2018-11-30 21:00:00', '2018-12-16 21:00:00', 0, '0.00', '50.00', '350.00');

-- --------------------------------------------------------

--
-- Структура таблицы `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) UNSIGNED NOT NULL,
  `user` int(11) UNSIGNED NOT NULL,
  `type` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `amount` decimal(13,2) UNSIGNED NOT NULL,
  `description` varchar(255) NOT NULL,
  `service` varchar(255) NOT NULL,
  `service_id` int(11) UNSIGNED NOT NULL,
  `ts` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `transactions`
--

INSERT INTO `transactions` (`id`, `user`, `type`, `amount`, `description`, `service`, `service_id`, `ts`) VALUES
(1, 12, 0, '1000.00', 'PaySystem', '', 0, '2018-10-23 22:23:59'),
(2, 12, 1, '120.00', ' 3ds Max Базовое моделирование (Декабрь 2018)', 'group', 1, '2018-10-23 22:24:05'),
(3, 12, 1, '50.00', 'Визуализация в 3ds Max (Декабрь 2018)', 'group', 2, '2018-10-31 09:39:55');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `active` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `hash` varchar(255) NOT NULL,
  `ts_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ts_modify` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `role` tinyint(3) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `active`, `hash`, `ts_created`, `ts_modify`, `role`) VALUES
(1, 'admin@admin.com', 'b1285d4b43914cc9980ff65d3f54031d0f908e72', 1, '', '2018-07-08 13:31:03', '2018-07-08 13:31:03', 5),
(6, 'test2@test.com', 'b1285d4b43914cc9980ff65d3f54031d0f908e72', 1, '88544a2a73f9591e3ffc1b1cd39f7f1da471b9bf', '2018-07-12 20:54:04', '2018-07-12 20:54:04', 0),
(7, 'test3@test.com', 'b1285d4b43914cc9980ff65d3f54031d0f908e72', 1, '68348e8594ec8564a659c78ee92ca32b693b42e3', '2018-07-12 20:54:34', '2018-07-12 20:54:34', 0),
(8, 'test4@test.com', 'b1285d4b43914cc9980ff65d3f54031d0f908e72', 1, '9f9a59fb17503988d3fb44170d631ece1c572f7f', '2018-07-12 20:55:19', '2018-07-12 20:55:19', 0),
(9, 'test5@test.com', 'b1285d4b43914cc9980ff65d3f54031d0f908e72', 1, 'f6d626c8a568d9a3ce4df8568c7df7d8d7449255', '2018-07-12 20:55:53', '2018-07-12 20:55:53', 0),
(10, 'test6@test.com', 'b1285d4b43914cc9980ff65d3f54031d0f908e72', 1, 'd83fe4571e7048fb3b69df86a4cce7156044b655', '2018-07-12 20:56:55', '2018-07-12 20:56:55', 0),
(11, 'test7@test.com', 'b1285d4b43914cc9980ff65d3f54031d0f908e72', 1, '3a0fcae89607d4bfdfffca3c7d90efe3706e5ce4', '2018-07-12 20:57:31', '2018-07-12 20:57:31', 0),
(12, 'test8@test.com', 'b1285d4b43914cc9980ff65d3f54031d0f908e72', 1, 'b7b9097ef93b468195d22f17754e79bedbcc536a', '2018-07-14 15:44:09', '2018-07-14 15:44:09', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `video`
--

CREATE TABLE `video` (
  `source_id` int(11) UNSIGNED NOT NULL,
  `source_type` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `video_url` text,
  `type` varchar(5) NOT NULL,
  `ts` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `video`
--

INSERT INTO `video` (`source_id`, `source_type`, `code`, `video_url`, `type`, `ts`) VALUES
(1, 'lecture', 'K3_dFLX83t4', 'https://i.ytimg.com/vi/K3_dFLX83t4/hqdefault.jpg?sqp=-oaymwEjCNACELwBSFryq4qpAxUIARUAAAAAGAElAADIQj0AgKJDeAE=&rs=AOn4CLC4DqjidOs6XY1Povkauqv8QchF9g', 'img', '2018-10-26 06:25:21'),
(1, 'lecture', 'K3_dFLX83t4', 'https://r16---sn-n8v7kn7s.googlevideo.com/videoplayback?fvip=16&lmt=1471002290651351&key=yt6&itag=22&c=WEB&signature=C344473C9B98D1F873EA356C7BC08EB177872545.0B4B0BD06567E14EDEC566CFCAB8BE54642A5EBB&ratebypass=yes&source=youtube&nh=IgpwcjAzLnN2bzAzKgkxMjcuMC4wLjE%2C&sparams=dur%2Cei%2Cid%2Cinitcwndbps%2Cip%2Cipbits%2Citag%2Clmt%2Cmime%2Cmm%2Cmn%2Cms%2Cmv%2Cnh%2Cpl%2Cratebypass%2Crequiressl%2Csource%2Cexpire&expire=1540556721&initcwndbps=608750&ei=UbPSW8XdDM_m7QTIooqYCA&dur=817.574&mv=m&mt=1540534969&ms=au%2Conr&requiressl=yes&ip=195.68.136.74&ipbits=0&mn=sn-n8v7kn7s%2Csn-axq7sn7z&mm=31%2C26&pl=21&mime=video%2Fmp4&id=o-AEWycAnSPVOvAg0TYmlEbCSOr3PwXjtGP0_3AJGm1lYX', 'mp4', '2018-10-26 06:25:21'),
(1, 'lecture', 'K3_dFLX83t4', 'https://r16---sn-n8v7kn7s.googlevideo.com/videoplayback?fvip=16&lmt=1421577304040363&key=yt6&itag=43&c=WEB&signature=449CBE0248E731729C5F89213811DD1BF2751363.E02C8F78FBA86AC9385AF215B454E62861CB9A79&ratebypass=yes&source=youtube&nh=IgpwcjAzLnN2bzAzKgkxMjcuMC4wLjE%2C&clen=23739399&sparams=clen%2Cdur%2Cei%2Cgir%2Cid%2Cinitcwndbps%2Cip%2Cipbits%2Citag%2Clmt%2Cmime%2Cmm%2Cmn%2Cms%2Cmv%2Cnh%2Cpl%2Cratebypass%2Crequiressl%2Csource%2Cexpire&gir=yes&expire=1540556721&initcwndbps=608750&ei=UbPSW8XdDM_m7QTIooqYCA&dur=0.000&mv=m&mt=1540534969&ms=au%2Conr&requiressl=yes&ip=195.68.136.74&ipbits=0&mn=sn-n8v7kn7s%2Csn-axq7sn7z&mm=31%2C26&pl=21&mime=video%2Fwebm&id=o-AEWycAnSPVOvAg0TYmlEbCSOr3PwXjtGP0_3AJGm1lYX', 'webm', '2018-10-26 06:25:21'),
(1, 'review', '2vcHcAWZBcs', 'https://i.ytimg.com/vi/2vcHcAWZBcs/hqdefault.jpg?sqp=-oaymwEjCNACELwBSFryq4qpAxUIARUAAAAAGAElAADIQj0AgKJDeAE=&rs=AOn4CLDmqsQC8OzXr49253GJRaISw7HF-Q', 'img', '2018-10-26 06:25:22'),
(1, 'review', '2vcHcAWZBcs', 'https://r2---sn-8ph2xajvh-8vbl.googlevideo.com/videoplayback?source=youtube&dur=1462.090&pl=21&itag=22&mime=video%2Fmp4&txp=5531332&expire=1540556722&c=WEB&ei=UbPSW67_NIm57QS7jYOABA&fvip=5&mm=31%2C29&lmt=1537983646333547&requiressl=yes&ms=au%2Crdu&mt=1540534969&mv=m&key=yt6&ip=195.68.136.74&id=o-AJPtZL0wAxHUmyegPffG0MjrJOTUyRq1fyNB2-zlCWJi&sparams=dur%2Cei%2Cid%2Cinitcwndbps%2Cip%2Cipbits%2Citag%2Clmt%2Cmime%2Cmm%2Cmn%2Cms%2Cmv%2Cnh%2Cpl%2Cratebypass%2Crequiressl%2Csource%2Cexpire&mn=sn-8ph2xajvh-8vbl%2Csn-n8v7kn76&ratebypass=yes&nh=%2CIgpwcjAxLnN2bzA2KgkxMjcuMC4wLjE&signature=53645584F477AECBB37CA6EBBAA06C18F881436A.2642A1AF0EA06DFA8EFA09E5D476D22ED4DC14AD&initcwndbps=551250&ipbits=0', 'mp4', '2018-10-26 06:25:22'),
(1, 'review', '2vcHcAWZBcs', 'https://r2---sn-8ph2xajvh-8vbl.googlevideo.com/videoplayback?source=youtube&dur=0.000&pl=21&itag=43&mime=video%2Fwebm&txp=5511222&expire=1540556722&c=WEB&ei=UbPSW67_NIm57QS7jYOABA&fvip=5&sparams=clen%2Cdur%2Cei%2Cgir%2Cid%2Cinitcwndbps%2Cip%2Cipbits%2Citag%2Clmt%2Cmime%2Cmm%2Cmn%2Cms%2Cmv%2Cnh%2Cpl%2Cratebypass%2Crequiressl%2Csource%2Cexpire&gir=yes&lmt=1537990782572115&requiressl=yes&ms=au%2Crdu&mt=1540534969&mv=m&key=yt6&ip=195.68.136.74&id=o-AJPtZL0wAxHUmyegPffG0MjrJOTUyRq1fyNB2-zlCWJi&mm=31%2C29&mn=sn-8ph2xajvh-8vbl%2Csn-n8v7kn76&ratebypass=yes&nh=%2CIgpwcjAxLnN2bzA2KgkxMjcuMC4wLjE&signature=2588FFE9ADFEC2AF8CAC07882AD54DBBE130BC21.738C96C322C21AD02CFD0CF40C5F0954A5E9A834&initcwndbps=551250&ipbits=0&clen=110782713', 'webm', '2018-10-26 06:25:22'),
(2, 'lecture', 'oz-5KjJYsPU', 'https://i.ytimg.com/vi/oz-5KjJYsPU/hqdefault.jpg?sqp=-oaymwEjCNACELwBSFryq4qpAxUIARUAAAAAGAElAADIQj0AgKJDeAE=&rs=AOn4CLCDXEulMl9MFVQooEcOCUqvO-1LSg', 'img', '2018-10-26 06:25:22'),
(2, 'lecture', 'oz-5KjJYsPU', 'https://r1---sn-8ph2xajvh-8vbl.googlevideo.com/videoplayback?itag=22&pl=21&mime=video%2Fmp4&c=WEB&nh=%2CIgpwcjAzLnN2bzAzKgkxMjcuMC4wLjE&sparams=dur%2Cei%2Cid%2Cinitcwndbps%2Cip%2Cipbits%2Citag%2Clmt%2Cmime%2Cmm%2Cmn%2Cms%2Cmv%2Cnh%2Cpl%2Cratebypass%2Crequiressl%2Csource%2Cexpire&ipbits=0&signature=686C35A5A7B33B365E6C746F6E17E0992FD1FAFD.5B8673748589A90470AFC849738D9CC140B9A60B&ip=195.68.136.74&key=yt6&lmt=1470913308663084&dur=165.256&expire=1540556722&id=o-AIra7SkW_jcx7phgOXSmmF92Yx86cVu8vInilmY5BzlF&source=youtube&initcwndbps=551250&fvip=10&mm=31%2C29&mn=sn-8ph2xajvh-8vbl%2Csn-n8v7kn7z&ratebypass=yes&requiressl=yes&mt=1540534969&mv=m&ei=UrPSW77dJ8LnyQXb1LXwCw&ms=au%2Crdu', 'mp4', '2018-10-26 06:25:22'),
(2, 'lecture', 'oz-5KjJYsPU', 'https://r1---sn-8ph2xajvh-8vbl.googlevideo.com/videoplayback?itag=43&pl=21&mime=video%2Fwebm&c=WEB&nh=%2CIgpwcjAzLnN2bzAzKgkxMjcuMC4wLjE&sparams=clen%2Cdur%2Cei%2Cgir%2Cid%2Cinitcwndbps%2Cip%2Cipbits%2Citag%2Clmt%2Cmime%2Cmm%2Cmn%2Cms%2Cmv%2Cnh%2Cpl%2Cratebypass%2Crequiressl%2Csource%2Cexpire&ipbits=0&signature=47A506161907918C8FD3ED0979A34DD371F78C3C.2045C32C12E87599DAC81EBA835E9524D6FB6F49&ip=195.68.136.74&key=yt6&lmt=1421575659329811&dur=0.000&expire=1540556722&clen=4338063&id=o-AIra7SkW_jcx7phgOXSmmF92Yx86cVu8vInilmY5BzlF&source=youtube&initcwndbps=551250&fvip=10&mm=31%2C29&mn=sn-8ph2xajvh-8vbl%2Csn-n8v7kn7z&gir=yes&ratebypass=yes&requiressl=yes&mt=1540534969&mv=m&ei=UrPSW77dJ8LnyQXb1LXwCw&ms=au%2Crdu', 'webm', '2018-10-26 06:25:22'),
(2, 'review', '2vcHcAWZBcs', 'https://i.ytimg.com/vi/2vcHcAWZBcs/hqdefault.jpg?sqp=-oaymwEjCNACELwBSFryq4qpAxUIARUAAAAAGAElAADIQj0AgKJDeAE=&rs=AOn4CLDmqsQC8OzXr49253GJRaISw7HF-Q', 'img', '2018-10-26 06:25:23'),
(2, 'review', '2vcHcAWZBcs', 'https://r2---sn-8ph2xajvh-8vbl.googlevideo.com/videoplayback?ms=au%2Crdu&mt=1540534969&mv=m&txp=5531332&ip=195.68.136.74&signature=398C35D1D5AAB2D7CF3E88D2DA0AFCB1DAFE6865.AB090307532D762D03952ED1BCA159B1FDADB3E0&initcwndbps=551250&c=WEB&id=o-AKdgaLe7DSvC-0m3Fq6RRw3zxgzhZUlcbawKdxno5LTh&mm=31%2C29&mn=sn-8ph2xajvh-8vbl%2Csn-n8v7znse&ipbits=0&fvip=5&ei=U7PSW9rdEIOd7ASVzKfgCw&pl=21&ratebypass=yes&source=youtube&itag=22&requiressl=yes&nh=%2CIgpwcjA0LnN2bzAzKgkxMjcuMC4wLjE&expire=1540556723&sparams=dur%2Cei%2Cid%2Cinitcwndbps%2Cip%2Cipbits%2Citag%2Clmt%2Cmime%2Cmm%2Cmn%2Cms%2Cmv%2Cnh%2Cpl%2Cratebypass%2Crequiressl%2Csource%2Cexpire&mime=video%2Fmp4&key=yt6&dur=1462.090&lmt=1537983646333547', 'mp4', '2018-10-26 06:25:23'),
(2, 'review', '2vcHcAWZBcs', 'https://r2---sn-8ph2xajvh-8vbl.googlevideo.com/videoplayback?ms=au%2Crdu&mt=1540534969&mv=m&txp=5511222&ip=195.68.136.74&signature=836EEAB3D18577774C8943C59253D69E8719D7D5.50822854578C1A8E826E0C52BD2404A204DF7D23&initcwndbps=551250&c=WEB&id=o-AKdgaLe7DSvC-0m3Fq6RRw3zxgzhZUlcbawKdxno5LTh&gir=yes&clen=110782713&mn=sn-8ph2xajvh-8vbl%2Csn-n8v7znse&ipbits=0&fvip=5&mm=31%2C29&ei=U7PSW9rdEIOd7ASVzKfgCw&pl=21&ratebypass=yes&source=youtube&itag=43&requiressl=yes&nh=%2CIgpwcjA0LnN2bzAzKgkxMjcuMC4wLjE&expire=1540556723&sparams=clen%2Cdur%2Cei%2Cgir%2Cid%2Cinitcwndbps%2Cip%2Cipbits%2Citag%2Clmt%2Cmime%2Cmm%2Cmn%2Cms%2Cmv%2Cnh%2Cpl%2Cratebypass%2Crequiressl%2Csource%2Cexpire&mime=video%2Fwebm&key=yt6&dur=0.000&lmt=1537990782572115', 'webm', '2018-10-26 06:25:23'),
(3, 'lecture', '8X7ZOwpCC8I', 'https://i.ytimg.com/vi/8X7ZOwpCC8I/hqdefault.jpg?sqp=-oaymwEjCNACELwBSFryq4qpAxUIARUAAAAAGAElAADIQj0AgKJDeAE=&rs=AOn4CLB8o4b4soGiunnFEvTEQ5OkJDfjNA', 'img', '2018-10-26 06:25:24'),
(3, 'lecture', '8X7ZOwpCC8I', 'https://r2---sn-n8v7znss.googlevideo.com/videoplayback?ei=U7PSW9v8O6bW7gTXz6XAAg&initcwndbps=505000&dur=233.406&expire=1540556724&ipbits=0&id=o-ANa0cdmsYYHutw5GgDp6SNRqFetTdip2p91A8QZVtgwX&mime=video%2Fmp4&pl=21&mm=31%2C29&mn=sn-n8v7znss%2Csn-n8v7kn7k&ms=au%2Crdu&mt=1540534969&mv=m&ip=195.68.136.74&requiressl=yes&c=WEB&ratebypass=yes&signature=380FB32CBF8B7A6C6E811B3733D27EF09552F144.5742646B35853AA310A2F65E81F5C5A3234CB8AC&fvip=2&itag=22&key=yt6&lmt=1375441387684075&sparams=dur%2Cei%2Cid%2Cinitcwndbps%2Cip%2Cipbits%2Citag%2Clmt%2Cmime%2Cmm%2Cmn%2Cms%2Cmv%2Cnh%2Cpl%2Cratebypass%2Crequiressl%2Csource%2Cexpire&nh=IgpwcjAxLnN2bzA2KgkxMjcuMC4wLjE%2CIgpwcjAyLnN2bzA2KgkxMjcuMC4wLjE&source=youtube', 'mp4', '2018-10-26 06:25:24'),
(3, 'lecture', '8X7ZOwpCC8I', 'https://r2---sn-n8v7znss.googlevideo.com/videoplayback?ei=U7PSW9v8O6bW7gTXz6XAAg&initcwndbps=505000&dur=0.000&expire=1540556724&c=WEB&ipbits=0&id=o-ANa0cdmsYYHutw5GgDp6SNRqFetTdip2p91A8QZVtgwX&mime=video%2Fwebm&pl=21&mm=31%2C29&mn=sn-n8v7znss%2Csn-n8v7kn7k&ms=au%2Crdu&mt=1540534969&gir=yes&ip=195.68.136.74&requiressl=yes&mv=m&ratebypass=yes&signature=27A90B230F21A0B8E96DE552E486EC7FED0441CE.330C9FE2AC899626B712C503C6C3B0D2E9BD3163&fvip=2&itag=43&key=yt6&lmt=1375523052391985&sparams=clen%2Cdur%2Cei%2Cgir%2Cid%2Cinitcwndbps%2Cip%2Cipbits%2Citag%2Clmt%2Cmime%2Cmm%2Cmn%2Cms%2Cmv%2Cnh%2Cpl%2Cratebypass%2Crequiressl%2Csource%2Cexpire&nh=IgpwcjAxLnN2bzA2KgkxMjcuMC4wLjE%2CIgpwcjAyLnN2bzA2KgkxMjcuMC4wLjE&source=youtube&clen=4200955', 'webm', '2018-10-26 06:25:24'),
(3, 'review', '2vcHcAWZBcs', 'https://i.ytimg.com/vi/2vcHcAWZBcs/hqdefault.jpg?sqp=-oaymwEjCNACELwBSFryq4qpAxUIARUAAAAAGAElAADIQj0AgKJDeAE=&rs=AOn4CLDmqsQC8OzXr49253GJRaISw7HF-Q', 'img', '2018-10-26 06:25:24'),
(3, 'review', '2vcHcAWZBcs', 'https://r2---sn-8ph2xajvh-8vbl.googlevideo.com/videoplayback?txp=5531332&nh=%2CIgpwcjAxLnN2bzA2KgkxMjcuMC4wLjE&mime=video%2Fmp4&key=yt6&signature=9F94069DCCB9104C0303735AD15A6D253BFDC438.B540D318692190C7535E7B9BA098B5C1D79A22C0&ipbits=0&fvip=5&dur=1462.090&itag=22&ratebypass=yes&expire=1540556724&ip=195.68.136.74&requiressl=yes&mt=1540534969&mv=m&sparams=dur%2Cei%2Cid%2Cinitcwndbps%2Cip%2Cipbits%2Citag%2Clmt%2Cmime%2Cmm%2Cmn%2Cms%2Cmv%2Cnh%2Cpl%2Cratebypass%2Crequiressl%2Csource%2Cexpire&ms=au%2Crdu&mm=31%2C29&mn=sn-8ph2xajvh-8vbl%2Csn-n8v7znse&id=o-AN2U8CUBnvr9ZaUG_HZwQGXWbdYFjx1ZOk3JxriZF3F8&pl=21&initcwndbps=551250&lmt=1537983646333547&source=youtube&c=WEB&ei=VLPSW8DFJprnyAWx9qPYCg', 'mp4', '2018-10-26 06:25:24'),
(3, 'review', '2vcHcAWZBcs', 'https://r2---sn-8ph2xajvh-8vbl.googlevideo.com/videoplayback?txp=5511222&nh=%2CIgpwcjAxLnN2bzA2KgkxMjcuMC4wLjE&gir=yes&mime=video%2Fwebm&key=yt6&clen=110782713&itag=43&ipbits=0&fvip=5&dur=0.000&signature=D690ED40C258D2027EC450CBBA84790EAF8A5937.0F6F58657270D14CC7B8B12E8458BE60BF0DB802&ratebypass=yes&expire=1540556724&ip=195.68.136.74&requiressl=yes&mt=1540534969&mv=m&sparams=clen%2Cdur%2Cei%2Cgir%2Cid%2Cinitcwndbps%2Cip%2Cipbits%2Citag%2Clmt%2Cmime%2Cmm%2Cmn%2Cms%2Cmv%2Cnh%2Cpl%2Cratebypass%2Crequiressl%2Csource%2Cexpire&ms=au%2Crdu&mm=31%2C29&mn=sn-8ph2xajvh-8vbl%2Csn-n8v7znse&id=o-AN2U8CUBnvr9ZaUG_HZwQGXWbdYFjx1ZOk3JxriZF3F8&pl=21&initcwndbps=551250&lmt=1537990782572115&source=youtube&c=WEB&ei=VLPSW8DFJprnyAWx9qPYCg', 'webm', '2018-10-26 06:25:24'),
(4, 'lecture', 'x8XXOvIEVco', 'https://i.ytimg.com/vi/x8XXOvIEVco/hqdefault.jpg?sqp=-oaymwEjCNACELwBSFryq4qpAxUIARUAAAAAGAElAADIQj0AgKJDeAE=&rs=AOn4CLDXEvQIgff3-4fQBzF2AkCsYe2B6A', 'img', '2018-10-26 06:25:25'),
(4, 'lecture', 'x8XXOvIEVco', 'https://r10---sn-n8v7knee.googlevideo.com/videoplayback?expire=1540556725&mime=video%2Fmp4&pl=21&key=yt6&lmt=1416952247168795&ratebypass=yes&source=youtube&dur=69.590&nh=IgpwcjAzLnN2bzAzKgkxMjcuMC4wLjE%2CIgpwcjAzLnN2bzAzKgkxMjcuMC4wLjE&id=o-APbORlAuGaNzuvtHvm2lJ32ItXO5LF1f_7YN5C5DcGn4&c=WEB&signature=6413E4E525C409DB78ED206EA4978BB00FC80E75.8289D6B06555CEBC6CCE88022C42A4FE07FADE5C&ipbits=0&requiressl=yes&fvip=10&sparams=dur%2Cei%2Cid%2Cinitcwndbps%2Cip%2Cipbits%2Citag%2Clmt%2Cmime%2Cmm%2Cmn%2Cms%2Cmv%2Cnh%2Cpl%2Cratebypass%2Crequiressl%2Csource%2Cexpire&initcwndbps=608750&ms=au%2Crdu&itag=22&mt=1540534969&mv=m&ei=VbPSW7HyEujx7AS8zrXABA&mm=31%2C29&mn=sn-n8v7knee%2Csn-n8v7zns7&ip=195.68.136.74', 'mp4', '2018-10-26 06:25:25'),
(4, 'lecture', 'x8XXOvIEVco', 'https://r10---sn-n8v7knee.googlevideo.com/videoplayback?clen=5286593&expire=1540556725&mime=video%2Fwebm&pl=21&key=yt6&lmt=1416952278940089&ratebypass=yes&source=youtube&dur=0.000&nh=IgpwcjAzLnN2bzAzKgkxMjcuMC4wLjE%2CIgpwcjAzLnN2bzAzKgkxMjcuMC4wLjE&id=o-APbORlAuGaNzuvtHvm2lJ32ItXO5LF1f_7YN5C5DcGn4&c=WEB&gir=yes&signature=0DEE2756A407D32244CFE6348EDB97B8C35EEEBD.286ADE7E3A86334B951A534DC33E5FBFDEAAAAD2&ipbits=0&requiressl=yes&fvip=10&sparams=clen%2Cdur%2Cei%2Cgir%2Cid%2Cinitcwndbps%2Cip%2Cipbits%2Citag%2Clmt%2Cmime%2Cmm%2Cmn%2Cms%2Cmv%2Cnh%2Cpl%2Cratebypass%2Crequiressl%2Csource%2Cexpire&initcwndbps=608750&ms=au%2Crdu&itag=43&mt=1540534969&mv=m&ei=VbPSW7HyEujx7AS8zrXABA&mm=31%2C29&mn=sn-n8v7knee%2Csn-n8v7zns7&ip=195.68.136.74', 'webm', '2018-10-26 06:25:25'),
(4, 'review', '2vcHcAWZBcs', 'https://i.ytimg.com/vi/2vcHcAWZBcs/hqdefault.jpg?sqp=-oaymwEjCNACELwBSFryq4qpAxUIARUAAAAAGAElAADIQj0AgKJDeAE=&rs=AOn4CLDmqsQC8OzXr49253GJRaISw7HF-Q', 'img', '2018-10-26 06:25:26'),
(4, 'review', '2vcHcAWZBcs', 'https://r2---sn-8ph2xajvh-8vbl.googlevideo.com/videoplayback?lmt=1537983646333547&ip=195.68.136.74&key=yt6&fvip=5&ms=au%2Crdu&mv=m&source=youtube&id=o-ADFpjb4PXCV49EV8E-EmpntniQW1MRROOJxfAhwpH9Pr&dur=1462.090&mn=sn-8ph2xajvh-8vbl%2Csn-n8v7kn76&mm=31%2C29&requiressl=yes&txp=5531332&nh=%2CIgpwcjA0LnN2bzAzKgkxMjcuMC4wLjE&ratebypass=yes&ipbits=0&sparams=dur%2Cei%2Cid%2Cinitcwndbps%2Cip%2Cipbits%2Citag%2Clmt%2Cmime%2Cmm%2Cmn%2Cms%2Cmv%2Cnh%2Cpl%2Cratebypass%2Crequiressl%2Csource%2Cexpire&expire=1540556725&mime=video%2Fmp4&mt=1540534969&signature=BE7B09F5359CC96A7AEE2F99154577EA498A61FC.600B6EACD61BE8535B48088699B78004771A6A7D&initcwndbps=551250&c=WEB&itag=22&pl=21&ei=VbPSW-7mN872yQXQnoqQCg', 'mp4', '2018-10-26 06:25:26'),
(4, 'review', '2vcHcAWZBcs', 'https://r2---sn-8ph2xajvh-8vbl.googlevideo.com/videoplayback?lmt=1537990782572115&ip=195.68.136.74&key=yt6&fvip=5&ms=au%2Crdu&mv=m&source=youtube&id=o-ADFpjb4PXCV49EV8E-EmpntniQW1MRROOJxfAhwpH9Pr&dur=0.000&mn=sn-8ph2xajvh-8vbl%2Csn-n8v7kn76&mm=31%2C29&requiressl=yes&txp=5511222&nh=%2CIgpwcjA0LnN2bzAzKgkxMjcuMC4wLjE&ratebypass=yes&ipbits=0&sparams=clen%2Cdur%2Cei%2Cgir%2Cid%2Cinitcwndbps%2Cip%2Cipbits%2Citag%2Clmt%2Cmime%2Cmm%2Cmn%2Cms%2Cmv%2Cnh%2Cpl%2Cratebypass%2Crequiressl%2Csource%2Cexpire&expire=1540556725&mime=video%2Fwebm&mt=1540534969&signature=B026B8B6622AFAE96651D331B9FD321847E5454B.599ACBA779006253A457F2981AAEAA57D840F3E6&initcwndbps=551250&c=WEB&itag=43&clen=110782713&gir=yes&pl=21&ei=VbPSW-7mN872yQXQnoqQCg', 'webm', '2018-10-26 06:25:26'),
(5, 'lecture', 'HhgcOTeaobI', 'https://i.ytimg.com/vi/HhgcOTeaobI/hqdefault.jpg?sqp=-oaymwEjCNACELwBSFryq4qpAxUIARUAAAAAGAElAADIQj0AgKJDeAE=&rs=AOn4CLARlKe_0R0g0aT9nVHetMi_7cEpFw', 'img', '2018-10-26 06:25:26'),
(5, 'lecture', 'HhgcOTeaobI', 'https://r2---sn-8ph2xajvh-8vbl.googlevideo.com/videoplayback?mime=video%2Fmp4&pl=21&expire=1540556726&ip=195.68.136.74&mm=31%2C29&mn=sn-8ph2xajvh-8vbl%2Csn-n8v7znsl&sparams=dur%2Cei%2Cid%2Cinitcwndbps%2Cip%2Cipbits%2Citag%2Clmt%2Cmime%2Cmm%2Cmn%2Cms%2Cmv%2Cnh%2Cpl%2Cratebypass%2Crequiressl%2Csource%2Cexpire&initcwndbps=551250&ms=au%2Crdu&itag=22&mt=1540534969&mv=m&signature=9CE589B10FC468EB704DBFD1D023AE37809C8554.CAAF140AFA556EA079121F49D750E4303E901E92&ipbits=0&requiressl=yes&fvip=14&ei=VrPSW9eXI8vZ7QT_5Kwg&c=WEB&nh=%2CIgpwcjA0LnN2bzAzKgkxMjcuMC4wLjE&id=o-AJJuOhGLgM8jTS4O-M2qSAVTuPKd-TzNudjowepeccmn&key=yt6&lmt=1471150964417950&ratebypass=yes&source=youtube&dur=57.887', 'mp4', '2018-10-26 06:25:26'),
(5, 'lecture', 'HhgcOTeaobI', 'https://r2---sn-8ph2xajvh-8vbl.googlevideo.com/videoplayback?mime=video%2Fwebm&pl=21&clen=1443331&expire=1540556726&ip=195.68.136.74&mm=31%2C29&mn=sn-8ph2xajvh-8vbl%2Csn-n8v7znsl&sparams=clen%2Cdur%2Cei%2Cgir%2Cid%2Cinitcwndbps%2Cip%2Cipbits%2Citag%2Clmt%2Cmime%2Cmm%2Cmn%2Cms%2Cmv%2Cnh%2Cpl%2Cratebypass%2Crequiressl%2Csource%2Cexpire&initcwndbps=551250&ms=au%2Crdu&itag=43&mt=1540534969&mv=m&gir=yes&signature=A92037C46C9BB88646E721DBD14FD513201A6B2C.7A2A5609AACA37250176CF08651212F1A7C4E2B0&ipbits=0&requiressl=yes&fvip=14&ei=VrPSW9eXI8vZ7QT_5Kwg&c=WEB&nh=%2CIgpwcjA0LnN2bzAzKgkxMjcuMC4wLjE&id=o-AJJuOhGLgM8jTS4O-M2qSAVTuPKd-TzNudjowepeccmn&key=yt6&lmt=1360440268499309&ratebypass=yes&source=youtube&dur=0.000', 'webm', '2018-10-26 06:25:26'),
(5, 'review', '2vcHcAWZBcs', 'https://i.ytimg.com/vi/2vcHcAWZBcs/hqdefault.jpg?sqp=-oaymwEjCNACELwBSFryq4qpAxUIARUAAAAAGAElAADIQj0AgKJDeAE=&rs=AOn4CLDmqsQC8OzXr49253GJRaISw7HF-Q', 'img', '2018-10-26 06:25:27'),
(5, 'review', '2vcHcAWZBcs', 'https://r2---sn-8ph2xajvh-8vbl.googlevideo.com/videoplayback?dur=1462.090&itag=22&pl=21&source=youtube&ratebypass=yes&c=WEB&expire=1540556727&mime=video%2Fmp4&signature=7587DA75489290C7AE8FA7893F41DA0219F5BC14.B7EB562C658A5AA8DB9ADA352AEB1E27CACC798C&pcm2cms=yes&nh=%2CIgpwcjA0LnN2bzAzKgkxMjcuMC4wLjE&ipbits=0&initcwndbps=551250&ip=195.68.136.74&key=yt6&mt=1540534969&mv=m&ms=au%2Crdu&mm=31%2C29&mn=sn-8ph2xajvh-8vbl%2Csn-n8v7znse&id=o-AGn99iUHG0vu5ZLtrfTAzftPlx2OaJKJ5NHzi73iPM1X&pcm2=no&lmt=1537983646333547&sparams=dur%2Cei%2Cid%2Cinitcwndbps%2Cip%2Cipbits%2Citag%2Clmt%2Cmime%2Cmm%2Cmn%2Cms%2Cmv%2Cnh%2Cpcm2%2Cpcm2cms%2Cpl%2Cratebypass%2Crequiressl%2Csource%2Cexpire&ei=V7PSW53OCZfs7ATlw6PwBA&fvip=5&requiressl=yes&txp=5531332', 'mp4', '2018-10-26 06:25:27'),
(5, 'review', '2vcHcAWZBcs', 'https://r2---sn-8ph2xajvh-8vbl.googlevideo.com/videoplayback?dur=0.000&itag=43&pl=21&source=youtube&ratebypass=yes&c=WEB&expire=1540556727&mime=video%2Fwebm&signature=A00AFDAEAFD86A1C376E3B0791F6E2244ABECCC5.72A6F1C51EDF85551B9F77058114AEA00CCCCEB5&pcm2cms=yes&nh=%2CIgpwcjA0LnN2bzAzKgkxMjcuMC4wLjE&clen=110782713&ipbits=0&initcwndbps=551250&gir=yes&ip=195.68.136.74&key=yt6&mt=1540534969&mv=m&ms=au%2Crdu&mm=31%2C29&mn=sn-8ph2xajvh-8vbl%2Csn-n8v7znse&id=o-AGn99iUHG0vu5ZLtrfTAzftPlx2OaJKJ5NHzi73iPM1X&pcm2=no&lmt=1537990782572115&sparams=clen%2Cdur%2Cei%2Cgir%2Cid%2Cinitcwndbps%2Cip%2Cipbits%2Citag%2Clmt%2Cmime%2Cmm%2Cmn%2Cms%2Cmv%2Cnh%2Cpcm2%2Cpcm2cms%2Cpl%2Cratebypass%2Crequiressl%2Csource%2Cexpire&ei=V7PSW53OCZfs7ATlw6PwBA&fvip=5&requiressl=yes&txp=5511222', 'webm', '2018-10-26 06:25:27'),
(6, 'lecture', 'oz-5KjJYsPU', 'https://i.ytimg.com/vi/oz-5KjJYsPU/hqdefault.jpg?sqp=-oaymwEjCNACELwBSFryq4qpAxUIARUAAAAAGAElAADIQj0AgKJDeAE=&rs=AOn4CLCDXEulMl9MFVQooEcOCUqvO-1LSg', 'img', '2018-10-26 06:25:28'),
(6, 'lecture', 'oz-5KjJYsPU', 'https://r1---sn-8ph2xajvh-8vbl.googlevideo.com/videoplayback?expire=1540556727&ei=V7PSW9GDMsajyAWChbl4&ms=au%2Crdu&mt=1540535071&mv=m&ipbits=0&mm=31%2C29&mn=sn-8ph2xajvh-8vbl%2Csn-n8v7kn7z&requiressl=yes&id=o-AGjgQS7TkcnrISWmNqFd0pkfj1FCGJYCEp29-xzLegQ1&itag=22&dur=165.256&lmt=1470913308663084&key=yt6&ip=195.68.136.74&signature=B06D571C1D66EF847BA20B38C902284441F81AFA.6B9DE34780D094B4982782812E3A5B9BE06F9E2C&fvip=10&sparams=dur%2Cei%2Cid%2Cinitcwndbps%2Cip%2Cipbits%2Citag%2Clmt%2Cmime%2Cmm%2Cmn%2Cms%2Cmv%2Cnh%2Cpl%2Cratebypass%2Crequiressl%2Csource%2Cexpire&mime=video%2Fmp4&c=WEB&nh=%2CIgpwcjA0LnN2bzAzKgkxMjcuMC4wLjE&ratebypass=yes&source=youtube&pl=21&initcwndbps=545000', 'mp4', '2018-10-26 06:25:28'),
(6, 'lecture', 'oz-5KjJYsPU', 'https://r1---sn-8ph2xajvh-8vbl.googlevideo.com/videoplayback?expire=1540556727&ei=V7PSW9GDMsajyAWChbl4&ms=au%2Crdu&mt=1540535071&mv=m&ipbits=0&mm=31%2C29&mn=sn-8ph2xajvh-8vbl%2Csn-n8v7kn7z&requiressl=yes&id=o-AGjgQS7TkcnrISWmNqFd0pkfj1FCGJYCEp29-xzLegQ1&itag=43&dur=0.000&clen=4338063&lmt=1421575659329811&key=yt6&ip=195.68.136.74&signature=3BE46AE21477E692D144AE94A6015EBA6EFCD22F.4DC55FB6DD3E7901FFB82758695B4D317342B2B0&fvip=10&sparams=clen%2Cdur%2Cei%2Cgir%2Cid%2Cinitcwndbps%2Cip%2Cipbits%2Citag%2Clmt%2Cmime%2Cmm%2Cmn%2Cms%2Cmv%2Cnh%2Cpl%2Cratebypass%2Crequiressl%2Csource%2Cexpire&mime=video%2Fwebm&c=WEB&nh=%2CIgpwcjA0LnN2bzAzKgkxMjcuMC4wLjE&ratebypass=yes&source=youtube&pl=21&initcwndbps=545000&gir=yes', 'webm', '2018-10-26 06:25:28'),
(6, 'review', 'aM-B7jZwX0s', 'https://i.ytimg.com/vi/aM-B7jZwX0s/hqdefault.jpg?sqp=-oaymwEjCNACELwBSFryq4qpAxUIARUAAAAAGAElAADIQj0AgKJDeAE=&rs=AOn4CLARghpSv5z-UGGt2Ih16Q2d63NfYA', 'img', '2018-10-26 10:05:15'),
(6, 'review', 'aM-B7jZwX0s', 'https://r5---sn-n8v7knee.googlevideo.com/videoplayback?source=youtube&ip=195.68.136.74&ipbits=0&mv=m&pl=21&ei=2-bSW9zFBcXY7ATLlrrQBQ&ms=au%2Crdu&mm=31%2C29&expire=1540569915&id=o-AGltQelLASRKQYPRVlOYt14IRE4UT8tVwiBmz2rX6ZgG&fvip=5&lmt=1537814798245399&mt=1540548167&c=WEB&dur=602.534&ratebypass=yes&initcwndbps=653750&requiressl=yes&mime=video%2Fmp4&key=yt6&nh=IgpwcjA0LnN2bzAzKgkxMjcuMC4wLjE%2CIgpwcjAxLnN2bzA2KgkxMjcuMC4wLjE&itag=22&mn=sn-n8v7knee%2Csn-n8v7znsz&signature=47F4834F4A0D6E0433C3069B1F39BAE74CE4E3FF.C928CDB8B8D740EBCB25639CE211EB5D28B2BF5E&sparams=dur%2Cei%2Cid%2Cinitcwndbps%2Cip%2Cipbits%2Citag%2Clmt%2Cmime%2Cmm%2Cmn%2Cms%2Cmv%2Cnh%2Cpl%2Cratebypass%2Crequiressl%2Csource%2Cexpire', 'mp4', '2018-10-26 10:05:15'),
(6, 'review', 'aM-B7jZwX0s', 'https://r5---sn-n8v7knee.googlevideo.com/videoplayback?source=youtube&ip=195.68.136.74&ipbits=0&mv=m&pl=21&ei=2-bSW9zFBcXY7ATLlrrQBQ&ms=au%2Crdu&mm=31%2C29&expire=1540569915&id=o-AGltQelLASRKQYPRVlOYt14IRE4UT8tVwiBmz2rX6ZgG&clen=62069457&fvip=5&lmt=1537818120924586&mt=1540548167&c=WEB&dur=0.000&ratebypass=yes&initcwndbps=653750&requiressl=yes&gir=yes&mime=video%2Fwebm&key=yt6&nh=IgpwcjA0LnN2bzAzKgkxMjcuMC4wLjE%2CIgpwcjAxLnN2bzA2KgkxMjcuMC4wLjE&itag=43&mn=sn-n8v7knee%2Csn-n8v7znsz&signature=1F4D81DEB861F1832DD8C78352211E67C80384E2.C3B3FD23CD7B8DF7047DA7ECE606B998810FDEAD&sparams=clen%2Cdur%2Cei%2Cgir%2Cid%2Cinitcwndbps%2Cip%2Cipbits%2Citag%2Clmt%2Cmime%2Cmm%2Cmn%2Cms%2Cmv%2Cnh%2Cpl%2Cratebypass%2Crequiressl%2Csource%2Cexpire', 'webm', '2018-10-26 10:05:15'),
(7, 'lecture', 'HhgcOTeaobI', 'https://i.ytimg.com/vi/HhgcOTeaobI/hqdefault.jpg?sqp=-oaymwEjCNACELwBSFryq4qpAxUIARUAAAAAGAElAADIQj0AgKJDeAE=&rs=AOn4CLARlKe_0R0g0aT9nVHetMi_7cEpFw', 'img', '2018-10-26 06:25:28'),
(7, 'lecture', 'HhgcOTeaobI', 'https://r2---sn-8ph2xajvh-8vbl.googlevideo.com/videoplayback?source=youtube&dur=57.887&fvip=14&id=o-AG8itzLRUeWh9x87s5pa_UtgPopSohUsXeJGmrycsqYb&lmt=1471150964417950&itag=22&requiressl=yes&ip=195.68.136.74&expire=1540556728&initcwndbps=551250&ratebypass=yes&ipbits=0&nh=%2CIgpwcjAzLnN2bzAzKgkxMjcuMC4wLjE&mime=video%2Fmp4&c=WEB&sparams=dur%2Cei%2Cid%2Cinitcwndbps%2Cip%2Cipbits%2Citag%2Clmt%2Cmime%2Cmm%2Cmn%2Cms%2Cmv%2Cnh%2Cpl%2Cratebypass%2Crequiressl%2Csource%2Cexpire&signature=E2A024E81AEA625E1D526D5087A177B03F413BD4.A9A3B731C07C49F05FEA9DC475AC1E1EE7D24219&mt=1540534969&pl=21&mv=m&ei=WLPSW6K-G4LS7ATShJnIAg&ms=au%2Crdu&mm=31%2C29&mn=sn-8ph2xajvh-8vbl%2Csn-n8v7kn76&key=yt6', 'mp4', '2018-10-26 06:25:28'),
(7, 'lecture', 'HhgcOTeaobI', 'https://r2---sn-8ph2xajvh-8vbl.googlevideo.com/videoplayback?source=youtube&dur=0.000&fvip=14&id=o-AG8itzLRUeWh9x87s5pa_UtgPopSohUsXeJGmrycsqYb&lmt=1360440268499309&itag=43&requiressl=yes&ip=195.68.136.74&expire=1540556728&initcwndbps=551250&ratebypass=yes&ipbits=0&nh=%2CIgpwcjAzLnN2bzAzKgkxMjcuMC4wLjE&mime=video%2Fwebm&c=WEB&gir=yes&sparams=clen%2Cdur%2Cei%2Cgir%2Cid%2Cinitcwndbps%2Cip%2Cipbits%2Citag%2Clmt%2Cmime%2Cmm%2Cmn%2Cms%2Cmv%2Cnh%2Cpl%2Cratebypass%2Crequiressl%2Csource%2Cexpire&signature=0D8E0AB2FC4977BF3F0A7EFEAC16937D9F859531.AA05BE28F9C4079ED3358733000D0E2D065811DF&clen=1443331&mt=1540534969&pl=21&mv=m&ei=WLPSW6K-G4LS7ATShJnIAg&ms=au%2Crdu&mm=31%2C29&mn=sn-8ph2xajvh-8vbl%2Csn-n8v7kn76&key=yt6', 'webm', '2018-10-26 06:25:28'),
(8, 'lecture', 'HhgcOTeaobI', 'https://i.ytimg.com/vi/HhgcOTeaobI/hqdefault.jpg?sqp=-oaymwEjCNACELwBSFryq4qpAxUIARUAAAAAGAElAADIQj0AgKJDeAE=&rs=AOn4CLARlKe_0R0g0aT9nVHetMi_7cEpFw', 'img', '2018-10-26 06:25:29'),
(8, 'lecture', 'HhgcOTeaobI', 'https://r2---sn-8ph2xajvh-8vbl.googlevideo.com/videoplayback?fvip=14&ratebypass=yes&expire=1540556729&itag=22&id=o-ABFheUcGDdzYTgdQQ3N-l820LXu8GINRjD-19OjByd1h&mn=sn-8ph2xajvh-8vbl%2Csn-n8v7kn76&mm=31%2C29&pl=21&ip=195.68.136.74&ms=au%2Crdu&ei=WbPSW_ybA4XuyAWyhLWQCQ&mv=m&mt=1540534969&c=WEB&ipbits=0&initcwndbps=551250&mime=video%2Fmp4&sparams=dur%2Cei%2Cid%2Cinitcwndbps%2Cip%2Cipbits%2Citag%2Clmt%2Cmime%2Cmm%2Cmn%2Cms%2Cmv%2Cnh%2Cpl%2Cratebypass%2Crequiressl%2Csource%2Cexpire&key=yt6&signature=57008F7E81AF1AB54EF253363061EE74F78C8A04.D9D95864F932837F1D8E98674B87CFDDE59A2EE4&nh=%2CIgpwcjAzLnN2bzAzKgkxMjcuMC4wLjE&requiressl=yes&lmt=1471150964417950&dur=57.887&source=youtube', 'mp4', '2018-10-26 06:25:29'),
(8, 'lecture', 'HhgcOTeaobI', 'https://r2---sn-8ph2xajvh-8vbl.googlevideo.com/videoplayback?fvip=14&ratebypass=yes&expire=1540556729&itag=43&id=o-ABFheUcGDdzYTgdQQ3N-l820LXu8GINRjD-19OjByd1h&mn=sn-8ph2xajvh-8vbl%2Csn-n8v7kn76&mm=31%2C29&pl=21&ip=195.68.136.74&ms=au%2Crdu&ei=WbPSW_ybA4XuyAWyhLWQCQ&mv=m&mt=1540534969&c=WEB&ipbits=0&initcwndbps=551250&mime=video%2Fwebm&sparams=clen%2Cdur%2Cei%2Cgir%2Cid%2Cinitcwndbps%2Cip%2Cipbits%2Citag%2Clmt%2Cmime%2Cmm%2Cmn%2Cms%2Cmv%2Cnh%2Cpl%2Cratebypass%2Crequiressl%2Csource%2Cexpire&key=yt6&signature=34B008FD6203501E9350890DE050F6CDBC0C5C80.1B8096223BF4ADC2FC7DE27BA92A1EE05C150FD2&gir=yes&nh=%2CIgpwcjAzLnN2bzAzKgkxMjcuMC4wLjE&clen=1443331&requiressl=yes&lmt=1360440268499309&dur=0.000&source=youtube', 'webm', '2018-10-26 06:25:29'),
(15, 'review', '2vcHcAWZBcs', 'https://i.ytimg.com/vi/2vcHcAWZBcs/hqdefault.jpg?sqp=-oaymwEjCNACELwBSFryq4qpAxUIARUAAAAAGAElAADIQj0AgKJDeAE=&rs=AOn4CLDmqsQC8OzXr49253GJRaISw7HF-Q', 'img', '2018-10-26 06:25:29'),
(15, 'review', '2vcHcAWZBcs', 'https://r2---sn-8ph2xajvh-8vbl.googlevideo.com/videoplayback?ipbits=0&txp=5531332&c=WEB&ratebypass=yes&lmt=1537983646333547&initcwndbps=551250&source=youtube&dur=1462.090&nh=%2CIgpwcjAxLnN2bzA2KgkxMjcuMC4wLjE&expire=1540556729&ei=WbPSW9DYFLiF7AS2g6-YBQ&itag=22&requiressl=yes&signature=2B82B10514D22B0EFA7C985DF9A2B85338AAFA2C.1935EBFA43C778386F503A67C5CD4DDA432BA784&fvip=5&ms=au%2Crdu&mv=m&mt=1540534969&sparams=dur%2Cei%2Cid%2Cinitcwndbps%2Cip%2Cipbits%2Citag%2Clmt%2Cmime%2Cmm%2Cmn%2Cms%2Cmv%2Cnh%2Cpl%2Cratebypass%2Crequiressl%2Csource%2Cexpire&pl=21&id=o-AGuyLJbaMGybznaBGt-4N6TA3CiwsVGQNmzUpEtmcvJ4&mime=video%2Fmp4&mm=31%2C29&mn=sn-8ph2xajvh-8vbl%2Csn-n8v7znse&ip=195.68.136.74&key=yt6', 'mp4', '2018-10-26 06:25:29'),
(15, 'review', '2vcHcAWZBcs', 'https://r2---sn-8ph2xajvh-8vbl.googlevideo.com/videoplayback?ipbits=0&txp=5511222&c=WEB&ratebypass=yes&lmt=1537990782572115&initcwndbps=551250&source=youtube&dur=0.000&clen=110782713&nh=%2CIgpwcjAxLnN2bzA2KgkxMjcuMC4wLjE&expire=1540556729&ei=WbPSW9DYFLiF7AS2g6-YBQ&itag=43&requiressl=yes&signature=A98CD36B8A2A8837411C6A135B11FAC4E38C3597.3AAB4603A8A945B122BB0A7960DC837B1D69BB64&fvip=5&ms=au%2Crdu&mv=m&mt=1540534969&sparams=clen%2Cdur%2Cei%2Cgir%2Cid%2Cinitcwndbps%2Cip%2Cipbits%2Citag%2Clmt%2Cmime%2Cmm%2Cmn%2Cms%2Cmv%2Cnh%2Cpl%2Cratebypass%2Crequiressl%2Csource%2Cexpire&pl=21&id=o-AGuyLJbaMGybznaBGt-4N6TA3CiwsVGQNmzUpEtmcvJ4&mime=video%2Fwebm&gir=yes&mm=31%2C29&mn=sn-8ph2xajvh-8vbl%2Csn-n8v7znse&ip=195.68.136.74&key=yt6', 'webm', '2018-10-26 06:25:29');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `courses_groups`
--
ALTER TABLE `courses_groups`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `lectures`
--
ALTER TABLE `lectures`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `lectures_groups`
--
ALTER TABLE `lectures_groups`
  ADD PRIMARY KEY (`group_id`,`lecture_id`);

--
-- Индексы таблицы `lectures_homework`
--
ALTER TABLE `lectures_homework`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `lectures_status`
--
ALTER TABLE `lectures_status`
  ADD PRIMARY KEY (`lecture_id`,`user`);

--
-- Индексы таблицы `review`
--
ALTER TABLE `review`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `streams`
--
ALTER TABLE `streams`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `subscription`
--
ALTER TABLE `subscription`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `video`
--
ALTER TABLE `video`
  ADD PRIMARY KEY (`source_id`,`source_type`,`type`) USING BTREE;

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT для таблицы `courses_groups`
--
ALTER TABLE `courses_groups`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT для таблицы `files`
--
ALTER TABLE `files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT для таблицы `lectures`
--
ALTER TABLE `lectures`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT для таблицы `lectures_homework`
--
ALTER TABLE `lectures_homework`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT для таблицы `review`
--
ALTER TABLE `review`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT для таблицы `streams`
--
ALTER TABLE `streams`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT для таблицы `subscription`
--
ALTER TABLE `subscription`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT для таблицы `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

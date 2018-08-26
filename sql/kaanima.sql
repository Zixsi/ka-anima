-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Авг 25 2018 г., 14:04
-- Версия сервера: 5.7.23-0ubuntu0.18.04.1
-- Версия PHP: 7.1.20-1+ubuntu18.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
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
  `price_month` decimal(6,2) UNSIGNED NOT NULL,
  `price_full` decimal(6,2) UNSIGNED NOT NULL,
  `author` int(11) UNSIGNED NOT NULL,
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `active` tinyint(1) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `courses`
--

INSERT INTO `courses` (`id`, `name`, `description`, `type`, `img`, `price_month`, `price_full`, `author`, `ts`, `active`) VALUES
(1, ' 3ds Max Базовое моделирование', 'Курс 3ds Max Базовое моделирование — это первая ступень в изучении самого популярного редактора трехмерной графики 3ds Max. Рекомедуем начинать именно с этого модуля, потому что в нём чётко и системно изложен основополагающий материал.', 0, 0, '12.00', '120.55', 12, '2018-07-15 08:42:54', 1),
(2, 'Визуализация в 3ds Max', 'Курс Визуализация в 3ds Max является логическим продолжением курса Базового моделирования (модуль 201). В этом курсе слушатели научатся создавать материалы и выполнять светопостановку в сценах 3ds Max используя физические материалы и фотометрические источники света, а так же визуализировать проекты с использованием рендеров 3ds Max Scanline и ART (Autodesk Raytracer). Курс необходим начинающим ', 0, 0, '55.63', '320.19', 12, '2018-07-15 08:48:29', 1),
(3, '3ds Max + Corona Интерьер', 'Курс 3ds Max интерьерное 3d-моделирование — это практикум по созданию интерьера. От слушателей требуется знание всех тем базового курса: 201. Базового моделирования и 202. Визуализации в 3ds Max. В любом проекте интерьера необходимо уметь работать с большими набором объектов, создавать сложные модели, уметь оптимизировать и настраивать рабочий процесс. ', 0, 0, '98.00', '612.00', 12, '2018-07-16 19:56:16', 1);

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
(1, '1-072018', 1, '2018-06-30 21:00:00', NULL),
(2, '1-032018', 1, '2018-02-28 21:00:00', NULL),
(4, '3-062018', 3, '2018-08-31 21:00:00', NULL),
(5, '2-082018', 2, '2018-07-31 21:00:00', NULL),
(6, '1-102018', 1, '2018-09-30 21:00:00', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `files`
--

CREATE TABLE `files` (
  `id` int(11) NOT NULL,
  `path` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `ext` varchar(10) DEFAULT NULL,
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
(1, 1, 1, '01 - Первая лекция 3D Max', 'Первая лекция описание', NULL, 0, 'https://www.youtube.com/embed/K3_dFLX83t4', '2018-07-15 11:54:06', '2018-07-15 11:54:06', 500),
(2, 1, 1, '02 - Вторая лекция 3D Max', 'Вторая лекция описание', NULL, 0, 'https://www.youtube.com/embed/oz-5KjJYsPU', '2018-07-15 11:54:48', '2018-07-15 11:54:48', 500),
(3, 1, 2, 'Лекция 1', 'Лекция 1', NULL, 0, 'https://www.youtube.com/embed/8X7ZOwpCC8I', '2018-08-01 18:20:54', '2018-08-01 18:20:54', 500);

-- --------------------------------------------------------

--
-- Структура таблицы `lectures_groups`
--

CREATE TABLE `lectures_groups` (
  `group_id` int(11) UNSIGNED NOT NULL,
  `lecture_id` int(11) UNSIGNED NOT NULL,
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `lectures_homework`
--

CREATE TABLE `lectures_homework` (
  `id` int(11) UNSIGNED NOT NULL,
  `lecture_id` int(11) UNSIGNED NOT NULL,
  `user` int(11) UNSIGNED NOT NULL,
  `type` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `video_url` varchar(255) DEFAULT NULL,
  `file` int(11) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
-- Структура таблицы `lectures_video`
--

CREATE TABLE `lectures_video` (
  `source_id` int(11) UNSIGNED NOT NULL,
  `source_type` varchar(255) DEFAULT NULL,
  `video_url` varchar(255) DEFAULT NULL,
  `type` varchar(5) NOT NULL,
  `ts` timestamp NULL DEFAULT NULL
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
-- Структура таблицы `subscription`
--

CREATE TABLE `subscription` (
  `id` int(11) UNSIGNED NOT NULL,
  `user` int(11) UNSIGNED NOT NULL,
  `type` tinyint(1) UNSIGNED NOT NULL,
  `service` int(11) UNSIGNED NOT NULL,
  `ts_end` timestamp NULL DEFAULT NULL,
  `price_month` decimal(13,2) UNSIGNED NOT NULL,
  `price_full` decimal(13,2) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `subscription`
--

INSERT INTO `subscription` (`id`, `user`, `type`, `service`, `ts_end`, `price_month`, `price_full`) VALUES
(1, 12, 0, 5, NULL, '55.63', '320.19'),
(3, 12, 0, 6, NULL, '12.00', '120.55');

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
(1, 'test@test.com', 'b1285d4b43914cc9980ff65d3f54031d0f908e72', 1, '', '2018-07-08 13:31:03', '2018-07-08 13:31:03', 0),
(6, 'test2@test.com', 'b1285d4b43914cc9980ff65d3f54031d0f908e72', 1, '88544a2a73f9591e3ffc1b1cd39f7f1da471b9bf', '2018-07-12 20:54:04', '2018-07-12 20:54:04', 0),
(7, 'test3@test.com', 'b1285d4b43914cc9980ff65d3f54031d0f908e72', 1, '68348e8594ec8564a659c78ee92ca32b693b42e3', '2018-07-12 20:54:34', '2018-07-12 20:54:34', 0),
(8, 'test4@test.com', 'b1285d4b43914cc9980ff65d3f54031d0f908e72', 1, '9f9a59fb17503988d3fb44170d631ece1c572f7f', '2018-07-12 20:55:19', '2018-07-12 20:55:19', 0),
(9, 'test5@test.com', 'b1285d4b43914cc9980ff65d3f54031d0f908e72', 1, 'f6d626c8a568d9a3ce4df8568c7df7d8d7449255', '2018-07-12 20:55:53', '2018-07-12 20:55:53', 0),
(10, 'test6@test.com', 'b1285d4b43914cc9980ff65d3f54031d0f908e72', 1, 'd83fe4571e7048fb3b69df86a4cce7156044b655', '2018-07-12 20:56:55', '2018-07-12 20:56:55', 0),
(11, 'test7@test.com', 'b1285d4b43914cc9980ff65d3f54031d0f908e72', 1, '3a0fcae89607d4bfdfffca3c7d90efe3706e5ce4', '2018-07-12 20:57:31', '2018-07-12 20:57:31', 0),
(12, 'test8@test.com', 'b1285d4b43914cc9980ff65d3f54031d0f908e72', 1, 'b7b9097ef93b468195d22f17754e79bedbcc536a', '2018-07-14 15:44:09', '2018-07-14 15:44:09', 0);

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
-- Индексы таблицы `lectures_video`
--
ALTER TABLE `lectures_video`
  ADD PRIMARY KEY (`source_id`,`type`);

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
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `lectures`
--
ALTER TABLE `lectures`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `lectures_homework`
--
ALTER TABLE `lectures_homework`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `subscription`
--
ALTER TABLE `subscription`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

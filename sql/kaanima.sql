-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Июл 31 2018 г., 23:27
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
  `description` text NOT NULL,
  `period` tinyint(3) UNSIGNED NOT NULL,
  `price_month` decimal(6,2) UNSIGNED NOT NULL,
  `price_full` decimal(6,2) UNSIGNED NOT NULL,
  `author` int(11) UNSIGNED NOT NULL,
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `active` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `courses`
--

INSERT INTO `courses` (`id`, `name`, `description`, `period`, `price_month`, `price_full`, `author`, `ts`, `active`) VALUES
(1, ' 3ds Max Базовое моделирование', 'Курс 3ds Max Базовое моделирование — это первая ступень в изучении самого популярного редактора трехмерной графики 3ds Max. Рекомедуем начинать именно с этого модуля, потому что в нём чётко и системно изложен основополагающий материал.', 6, '12.00', '120.55', 12, '2018-07-15 08:42:54', 1),
(2, 'Визуализация в 3ds Max', 'Курс Визуализация в 3ds Max является логическим продолжением курса Базового моделирования (модуль 201). В этом курсе слушатели научатся создавать материалы и выполнять светопостановку в сценах 3ds Max используя физические материалы и фотометрические источники света, а так же визуализировать проекты с использованием рендеров 3ds Max Scanline и ART (Autodesk Raytracer). Курс необходим начинающим ', 3, '55.63', '320.19', 12, '2018-07-15 08:48:29', 1),
(3, '3ds Max + Corona Интерьер', 'Курс 3ds Max интерьерное 3d-моделирование — это практикум по созданию интерьера. От слушателей требуется знание всех тем базового курса: 201. Базового моделирования и 202. Визуализации в 3ds Max. В любом проекте интерьера необходимо уметь работать с большими набором объектов, создавать сложные модели, уметь оптимизировать и настраивать рабочий процесс. ', 3, '98.00', '612.00', 12, '2018-07-16 19:56:16', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `courses_groups`
--

CREATE TABLE `courses_groups` (
  `id` int(11) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `course` int(11) UNSIGNED NOT NULL,
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `courses_groups`
--

INSERT INTO `courses_groups` (`id`, `code`, `course`, `ts`) VALUES
(1, '1-072018', 1, '2018-06-30 21:00:00'),
(2, '1-032018', 1, '2018-02-28 21:00:00'),
(4, '3-062018', 3, '2018-08-31 21:00:00'),
(5, '2-082018', 2, '2018-07-31 21:00:00'),
(6, '1-102018', 1, '2018-09-30 21:00:00');

-- --------------------------------------------------------

--
-- Структура таблицы `courses_subscription`
--

CREATE TABLE `courses_subscription` (
  `id` int(11) UNSIGNED NOT NULL,
  `user` int(11) UNSIGNED NOT NULL,
  `course_group` int(11) NOT NULL,
  `price_month` decimal(13,2) UNSIGNED NOT NULL,
  `price_full` decimal(13,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `courses_subscription`
--

INSERT INTO `courses_subscription` (`id`, `user`, `course_group`, `price_month`, `price_full`) VALUES
(1, 12, 5, '55.63', '320.19'),
(3, 12, 6, '12.00', '120.55');

-- --------------------------------------------------------

--
-- Структура таблицы `lectures`
--

CREATE TABLE `lectures` (
  `id` int(10) UNSIGNED NOT NULL,
  `active` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `course` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `video` varchar(255) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modify` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `sort` smallint(5) UNSIGNED NOT NULL DEFAULT '500'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `lectures`
--

INSERT INTO `lectures` (`id`, `active`, `course`, `name`, `description`, `video`, `created`, `modify`, `sort`) VALUES
(1, 1, 1, '01 - Первая лекция 3D Max', 'Первая лекция описание', 'https://youtu.be/K3_dFLX83t4', '2018-07-15 11:54:06', '2018-07-15 11:54:06', 500),
(2, 1, 1, '02 - Вторая лекция 3D Max', 'Вторая лекция описание', 'https://youtu.be/oz-5KjJYsPU', '2018-07-15 11:54:48', '2018-07-15 11:54:48', 500);

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
-- Структура таблицы `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) UNSIGNED NOT NULL,
  `user` int(11) UNSIGNED NOT NULL,
  `type` varchar(255) NOT NULL,
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
(1, 12, 'IN', '100.00', 'add balance', '', 0, '2018-07-17 23:52:46'),
(2, 12, 'IN', '100.00', 'add balance', '', 0, '2018-07-17 23:54:53'),
(3, 12, 'IN', '15.35', 'add balance', '', 0, '2018-07-17 23:55:11'),
(4, 12, 'IN', '1.18', 'add balance', '', 0, '2018-07-17 23:58:32'),
(5, 12, 'IN', '1.00', 'add balance', '', 0, '2018-07-17 23:59:11'),
(6, 12, 'IN', '0.01', 'add balance', '', 0, '2018-07-17 23:59:18'),
(7, 12, 'IN', '100.00', 'add balance', '', 0, '2018-07-18 00:08:51'),
(8, 12, 'IN', '50.00', 'add balance', '', 0, '2018-07-19 21:23:19'),
(9, 12, 'IN', '100000.00', 'add balance', '', 0, '2018-07-19 21:56:31'),
(16, 12, 'IN', '100.00', 'add balance', '', 0, '2018-07-31 22:35:26'),
(17, 12, 'OUT', '55.63', 'Subscibe 2-082018', 'group', 5, '2018-07-31 22:49:57'),
(19, 12, 'OUT', '120.55', 'Subscibe 1-102018', 'group', 6, '2018-07-31 22:50:23');

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
  `ts_modify` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `active`, `hash`, `ts_created`, `ts_modify`) VALUES
(1, 'test@test.com', 'b1285d4b43914cc9980ff65d3f54031d0f908e72', 1, '', '2018-07-08 13:31:03', '2018-07-08 13:31:03'),
(6, 'test2@test.com', 'b1285d4b43914cc9980ff65d3f54031d0f908e72', 1, '88544a2a73f9591e3ffc1b1cd39f7f1da471b9bf', '2018-07-12 20:54:04', '2018-07-12 20:54:04'),
(7, 'test3@test.com', 'b1285d4b43914cc9980ff65d3f54031d0f908e72', 1, '68348e8594ec8564a659c78ee92ca32b693b42e3', '2018-07-12 20:54:34', '2018-07-12 20:54:34'),
(8, 'test4@test.com', 'b1285d4b43914cc9980ff65d3f54031d0f908e72', 1, '9f9a59fb17503988d3fb44170d631ece1c572f7f', '2018-07-12 20:55:19', '2018-07-12 20:55:19'),
(9, 'test5@test.com', 'b1285d4b43914cc9980ff65d3f54031d0f908e72', 1, 'f6d626c8a568d9a3ce4df8568c7df7d8d7449255', '2018-07-12 20:55:53', '2018-07-12 20:55:53'),
(10, 'test6@test.com', 'b1285d4b43914cc9980ff65d3f54031d0f908e72', 1, 'd83fe4571e7048fb3b69df86a4cce7156044b655', '2018-07-12 20:56:55', '2018-07-12 20:56:55'),
(11, 'test7@test.com', 'b1285d4b43914cc9980ff65d3f54031d0f908e72', 1, '3a0fcae89607d4bfdfffca3c7d90efe3706e5ce4', '2018-07-12 20:57:31', '2018-07-12 20:57:31'),
(12, 'test8@test.com', 'b1285d4b43914cc9980ff65d3f54031d0f908e72', 1, 'b7b9097ef93b468195d22f17754e79bedbcc536a', '2018-07-14 15:44:09', '2018-07-14 15:44:09');

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
-- Индексы таблицы `courses_subscription`
--
ALTER TABLE `courses_subscription`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `lectures`
--
ALTER TABLE `lectures`
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
-- AUTO_INCREMENT для таблицы `courses_subscription`
--
ALTER TABLE `courses_subscription`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `lectures`
--
ALTER TABLE `lectures`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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

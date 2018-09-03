-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Сен 03 2018 г., 23:17
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
(1, ' 3ds Max Базовое моделирование', 'Курс 3ds Max Базовое моделирование — это первая ступень в изучении самого популярного редактора трехмерной графики 3ds Max. Рекомедуем начинать именно с этого модуля, потому что в нём чётко и системно изложен основополагающий материал.', 0, 0, '12.00', '120.00', 12, '2018-07-15 08:42:54', 1),
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
(16, 'c1-201809', 1, '2018-08-31 21:00:00', '2018-10-14 21:00:00'),
(17, 'c2-201809', 2, '2018-08-31 21:00:00', '2018-09-16 21:00:00'),
(18, 'c3-201809', 3, '2018-08-31 21:00:00', '2018-09-09 21:00:00');

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
(1, 'e7f1002c1da6bd3f0e30a41f1a5dc5b9.JPG', 'image/jpeg', '/home/zixsi/public_html/kaanima-loc.ru/www/data/', '/home/zixsi/public_html/kaanima-loc.ru/www/data/e7f1002c1da6bd3f0e30a41f1a5dc5b9.JPG', 'e7f1002c1da6bd3f0e30a41f1a5dc5b9', 'условия.JPG', 'условия.JPG', '.JPG', 483.68, 1, 2559, 1304, 'jpeg', '2018-09-02 21:00:00'),
(2, '8562c18c3e2266cb1a69089b777c22b3.JPG', 'image/jpeg', '/home/zixsi/public_html/kaanima-loc.ru/www/data/', '/home/zixsi/public_html/kaanima-loc.ru/www/data/8562c18c3e2266cb1a69089b777c22b3.JPG', '8562c18c3e2266cb1a69089b777c22b3', 'мое_меню5.JPG', 'мое_меню5.JPG', '.JPG', 102.01, 1, 2560, 1303, 'jpeg', '2018-09-02 21:00:00');

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
(1, 1, 1, '01 - Первая лекция 3D Max', 'Первая лекция описание', 'Описание задачи 01 - Первая лекция 3D Max', 0, 'https://www.youtube.com/embed/K3_dFLX83t4', '2018-07-15 11:54:06', '2018-07-15 11:54:06', 500),
(2, 1, 1, '02 - Вторая лекция 3D Max', 'Вторая лекция описание', NULL, 0, 'https://www.youtube.com/embed/oz-5KjJYsPU', '2018-07-15 11:54:48', '2018-07-15 11:54:48', 500),
(3, 1, 2, 'Лекция 1', 'Лекция 1', NULL, 0, 'https://www.youtube.com/embed/8X7ZOwpCC8I', '2018-08-01 18:20:54', '2018-08-01 18:20:54', 500),
(4, 1, 1, 'Бонусная лекция', 'Бонусная лекция описание', '', 1, 'https://www.youtube.com/watch?v=x8XXOvIEVco', '2018-07-15 11:54:48', '2018-07-15 11:54:48', 500),
(5, 1, 1, '03 - Третья лекция 3D Max', 'Третья лекция описание', '', 0, 'https://www.youtube.com/watch?v=HhgcOTeaobI', '2018-07-15 11:54:48', '2018-07-15 11:54:48', 500),
(6, 1, 0, 'Вступительная лекция', 'Вступительная лекция', NULL, 1, 'https://www.youtube.com/embed/oz-5KjJYsPU', '2018-07-15 11:54:48', '2018-07-15 11:54:48', 0),
(7, 1, 1, '04 - Четвертая лекция 3D Max', 'Четвертая лекция описание', '', 0, 'https://www.youtube.com/watch?v=HhgcOTeaobI', '2018-07-15 11:54:48', '2018-07-15 11:54:48', 500),
(8, 1, 1, '05 - Пятая лекция 3D Max', 'Пятая лекция описание', '', 0, 'https://www.youtube.com/watch?v=HhgcOTeaobI', '2018-07-15 11:54:48', '2018-07-15 11:54:48', 500);

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
(16, 1, '2018-07-31 21:00:00'),
(16, 2, '2018-08-04 21:00:00'),
(16, 4, '2018-08-04 21:00:00'),
(16, 5, '2018-08-09 21:00:00'),
(16, 7, '2018-08-11 21:00:00'),
(16, 8, '2018-08-19 21:00:00'),
(17, 3, '2018-08-29 21:00:00');

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
  `comment` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `lectures_homework`
--

INSERT INTO `lectures_homework` (`id`, `group_id`, `lecture_id`, `user`, `type`, `video_url`, `file`, `comment`) VALUES
(1, 16, 1, 12, 0, NULL, 1, 'Условия'),
(2, 16, 1, 12, 0, NULL, 2, '');

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
  `video_url` text,
  `type` varchar(5) NOT NULL,
  `ts` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `lectures_video`
--

INSERT INTO `lectures_video` (`source_id`, `source_type`, `video_url`, `type`, `ts`) VALUES
(1, 'lecture', 'https://r2---sn-xguxaxjvh-8vbl.googlevideo.com/videoplayback?mn=sn-xguxaxjvh-8vbl%2Csn-n8v7kn7s&mm=31%2C29&gir=yes&id=o-AOo4vXfTL3w-ID3NplwdDKgwiXYK5jMALmZUrFKFMcGF&c=WEB&initcwndbps=1285000&clen=71176788&signature=C47330E98593C50B06F04106D282F82FC346ABB5.C75567E4B606A0706A98144570788F5C5E25C2C2&source=youtube&fvip=16&mv=m&mt=1536005586&ms=au%2Crdu&pl=22&ei=RZaNW9i0Gtrx7gTx8LuwDQ&aitags=133%2C134%2C135%2C136%2C137%2C160&ipbits=0&requiressl=yes&itag=137&keepalive=yes&lmt=1421577258241643&dur=817.480&key=yt6&mime=video%2Fmp4&ip=188.233.0.115&sparams=aitags%2Cclen%2Cdur%2Cei%2Cgir%2Cid%2Cinitcwndbps%2Cip%2Cipbits%2Citag%2Ckeepalive%2Clmt%2Cmime%2Cmm%2Cmn%2Cms%2Cmv%2Cnh%2Cpl%2Crequiressl%2Csource%2Cexpire&expire=1536027301&nh=%2CIgpwcjAyLnN2bzA1KgkxMjcuMC4wLjE', 'mp4', '2018-09-03 20:15:01'),
(2, 'lecture', 'https://r1---sn-xguxaxjvh-8vbl.googlevideo.com/videoplayback?fvip=10&nh=%2CIgpwcjAyLnN2bzA1KgkxMjcuMC4wLjE&clen=8790631&dur=165.166&requiressl=yes&initcwndbps=1285000&source=youtube&ei=RZaNW8X6MaHu7AScyIWgBA&keepalive=yes&lmt=1421575432239398&sparams=aitags%2Cclen%2Cdur%2Cei%2Cgir%2Cid%2Cinitcwndbps%2Cip%2Cipbits%2Citag%2Ckeepalive%2Clmt%2Cmime%2Cmm%2Cmn%2Cms%2Cmv%2Cnh%2Cpl%2Crequiressl%2Csource%2Cexpire&aitags=133%2C134%2C135%2C136%2C137%2C160&ipbits=0&signature=0973513DC981D5BE55C375D46B70AEC1F0D6440D.84812C1DAEAF6688B0E8B7C7DFEC4F64BADED359&id=o-AJTCHL55dtDSzCIW4idMvtCVUHxe1RCxVFycoaJ5umFP&mv=m&mt=1536005586&ms=au%2Crdu&mn=sn-xguxaxjvh-8vbl%2Csn-n8v7kn7z&mm=31%2C29&ip=188.233.0.115&pl=22&expire=1536027301&gir=yes&c=WEB&key=yt6&mime=video%2Fmp4&itag=137', 'mp4', '2018-09-03 20:15:02'),
(3, 'lecture', 'https://r3---sn-xguxaxjvh-8vbe.googlevideo.com/videoplayback?ms=au%2Crdu&mv=m&mt=1536005586&source=youtube&requiressl=yes&dur=233.200&id=o-AERK3u1esOMXUGrsloLbJNzGAqwWj15jFaGPcb27kLnS&mn=sn-xguxaxjvh-8vbe%2Csn-n8v7znss&pl=22&mm=31%2C29&expire=1536027302&ipbits=0&sparams=aitags%2Cclen%2Cdur%2Cei%2Cgir%2Cid%2Cinitcwndbps%2Cip%2Cipbits%2Citag%2Ckeepalive%2Clmt%2Cmime%2Cmm%2Cmn%2Cms%2Cmv%2Cnh%2Cpcm2%2Cpl%2Crequiressl%2Csource%2Cexpire&pcm2=yes&initcwndbps=1438750&lmt=1375470625642668&nh=%2CIgpwcjAxLnN2bzA1KgkxMjcuMC4wLjE&aitags=133%2C134%2C135%2C136%2C160%2C242%2C243%2C244%2C247%2C278&signature=8FEDE42422362795E8E9A52CAD546EC6B7EDE119.6046C2727A557B15962DA0B466727559C410F7D6&itag=136&key=yt6&mime=video%2Fmp4&ip=188.233.0.115&keepalive=yes&gir=yes&clen=14705544&fvip=2&ei=RpaNW_HSH-zw7ASUyoC4DA&c=WEB', 'mp4', '2018-09-03 20:15:02'),
(3, 'lecture', 'https://r3---sn-xguxaxjvh-8vbe.googlevideo.com/videoplayback?ms=au%2Crdu&mv=m&mt=1536005586&source=youtube&requiressl=yes&dur=233.200&id=o-AERK3u1esOMXUGrsloLbJNzGAqwWj15jFaGPcb27kLnS&mn=sn-xguxaxjvh-8vbe%2Csn-n8v7znss&pl=22&mm=31%2C29&expire=1536027302&ipbits=0&sparams=aitags%2Cclen%2Cdur%2Cei%2Cgir%2Cid%2Cinitcwndbps%2Cip%2Cipbits%2Citag%2Ckeepalive%2Clmt%2Cmime%2Cmm%2Cmn%2Cms%2Cmv%2Cnh%2Cpcm2%2Cpl%2Crequiressl%2Csource%2Cexpire&pcm2=yes&initcwndbps=1438750&lmt=1507235730841757&nh=%2CIgpwcjAxLnN2bzA1KgkxMjcuMC4wLjE&aitags=133%2C134%2C135%2C136%2C160%2C242%2C243%2C244%2C247%2C278&signature=D57353205B47D4FBF761977FE175C9A9F258A6A5.6EBD87434FA5FE362A7EFB71313784E1E0C48631&itag=247&key=yt6&mime=video%2Fwebm&ip=188.233.0.115&keepalive=yes&gir=yes&clen=4244905&fvip=2&ei=RpaNW_HSH-zw7ASUyoC4DA&c=WEB', 'webm', '2018-09-03 20:15:02'),
(5, 'lecture', 'https://r2---sn-xguxaxjvh-8vbl.googlevideo.com/videoplayback?source=youtube&sparams=aitags%2Cclen%2Cdur%2Cei%2Cgir%2Cid%2Cinitcwndbps%2Cip%2Cipbits%2Citag%2Ckeepalive%2Clmt%2Cmime%2Cmm%2Cmn%2Cms%2Cmv%2Cnh%2Cpl%2Crequiressl%2Csource%2Cexpire&signature=0B624D1D9DC4841E3E77D31BE4186FAA9A1298F8.70B7EE1BC3D58BABB0B88FBC332C6A51FF5EF023&itag=136&beids=9466588&expire=1536027303&nh=%2CIgpwcjAyLnN2bzA1KgkxMjcuMC4wLjE&keepalive=yes&mime=video%2Fmp4&gir=yes&key=yt6&requiressl=yes&initcwndbps=1285000&mt=1536005586&dur=57.801&aitags=133%2C134%2C135%2C136%2C160%2C242%2C243%2C244%2C247%2C278&lmt=1394402322904613&c=WEB&fvip=14&clen=5597877&id=o-AIx9UXXbqHJ1grllYifXf8dNxg0aPDaIgYDWmckzo-r-&mm=31%2C29&mn=sn-xguxaxjvh-8vbl%2Csn-n8v7znsl&ei=R5aNW9nuEvDw7gSrvKrgCg&ms=au%2Crdu&ipbits=0&pl=22&mv=m&ip=188.233.0.115', 'mp4', '2018-09-03 20:15:03'),
(5, 'lecture', 'https://r2---sn-xguxaxjvh-8vbl.googlevideo.com/videoplayback?source=youtube&sparams=aitags%2Cclen%2Cdur%2Cei%2Cgir%2Cid%2Cinitcwndbps%2Cip%2Cipbits%2Citag%2Ckeepalive%2Clmt%2Cmime%2Cmm%2Cmn%2Cms%2Cmv%2Cnh%2Cpl%2Crequiressl%2Csource%2Cexpire&signature=7A69693A7F59543B2FFD05A23F10CEBF92557E26.8388CEB695B15C47ED927341A747D158F68C7E36&itag=247&beids=9466588&expire=1536027303&nh=%2CIgpwcjAyLnN2bzA1KgkxMjcuMC4wLjE&keepalive=yes&mime=video%2Fwebm&gir=yes&key=yt6&requiressl=yes&initcwndbps=1285000&mt=1536005586&dur=57.800&aitags=133%2C134%2C135%2C136%2C160%2C242%2C243%2C244%2C247%2C278&lmt=1435395786180088&c=WEB&fvip=14&clen=1575306&id=o-AIx9UXXbqHJ1grllYifXf8dNxg0aPDaIgYDWmckzo-r-&mm=31%2C29&mn=sn-xguxaxjvh-8vbl%2Csn-n8v7znsl&ei=R5aNW9nuEvDw7gSrvKrgCg&ms=au%2Crdu&ipbits=0&pl=22&mv=m&ip=188.233.0.115', 'webm', '2018-09-03 20:15:03'),
(6, 'lecture', 'https://r1---sn-xguxaxjvh-8vbl.googlevideo.com/videoplayback?c=WEB&aitags=133%2C134%2C135%2C136%2C137%2C160&initcwndbps=1285000&nh=%2CIgpwcjAxLnN2bzA1KgkxMjcuMC4wLjE&clen=8790631&requiressl=yes&ipbits=0&sparams=aitags%2Cclen%2Cdur%2Cei%2Cgir%2Cid%2Cinitcwndbps%2Cip%2Cipbits%2Citag%2Ckeepalive%2Clmt%2Cmime%2Cmm%2Cmn%2Cms%2Cmv%2Cnh%2Cpl%2Crequiressl%2Csource%2Cexpire&expire=1536027303&fvip=10&mime=video%2Fmp4&gir=yes&ip=188.233.0.115&key=yt6&pl=22&signature=2B72260001B848F6709315D3F29E243A615E065F.75F8A721E63D4712B182EFD3351A777C19C163A6&mm=31%2C29&mn=sn-xguxaxjvh-8vbl%2Csn-n8v7znse&itag=137&mt=1536005586&mv=m&id=o-AAlch1Y1ssINIRVz-QLMjE3KZiwpfNQzH4OFsPNHr-nX&ei=R5aNW6qRI9jk7gTh36KgBA&ms=au%2Crdu&source=youtube&lmt=1421575432239398&keepalive=yes&dur=165.166', 'mp4', '2018-09-03 20:15:03'),
(7, 'lecture', 'https://r2---sn-xguxaxjvh-8vbl.googlevideo.com/videoplayback?expire=1536027304&nh=%2CIgpwcjAyLnN2bzA1KgkxMjcuMC4wLjE&gir=yes&fvip=14&pl=22&keepalive=yes&requiressl=yes&ipbits=0&key=yt6&itag=136&mime=video%2Fmp4&aitags=133%2C134%2C135%2C136%2C160%2C242%2C243%2C244%2C247%2C278&signature=4869905951C03C63459782CF512391610BC0BE68.29B49EA5287D999D60F20398F2E6189D2D19B389&lmt=1394402322904613&clen=5597877&c=WEB&mn=sn-xguxaxjvh-8vbl%2Csn-n8v7kn76&mm=31%2C29&source=youtube&mv=m&mt=1536005586&ms=au%2Crdu&dur=57.801&ei=SJaNW7vPBoeJ7ATTyr7YDw&ip=188.233.0.115&initcwndbps=1285000&sparams=aitags%2Cclen%2Cdur%2Cei%2Cgir%2Cid%2Cinitcwndbps%2Cip%2Cipbits%2Citag%2Ckeepalive%2Clmt%2Cmime%2Cmm%2Cmn%2Cms%2Cmv%2Cnh%2Cpl%2Crequiressl%2Csource%2Cexpire&id=o-ANAjVpGzxgf9RE3jP012pr0QnPs9IFUbYF1xmnc8NYnW', 'mp4', '2018-09-03 20:15:04'),
(7, 'lecture', 'https://r2---sn-xguxaxjvh-8vbl.googlevideo.com/videoplayback?expire=1536027304&nh=%2CIgpwcjAyLnN2bzA1KgkxMjcuMC4wLjE&gir=yes&fvip=14&pl=22&keepalive=yes&requiressl=yes&ipbits=0&key=yt6&itag=247&mime=video%2Fwebm&aitags=133%2C134%2C135%2C136%2C160%2C242%2C243%2C244%2C247%2C278&signature=9A89860ED5ED818C89A369C8219F74E5D85247B2.A91EA2541D64BC70559F0FA8086890B003BA1514&lmt=1435395786180088&clen=1575306&c=WEB&mn=sn-xguxaxjvh-8vbl%2Csn-n8v7kn76&mm=31%2C29&source=youtube&mv=m&mt=1536005586&ms=au%2Crdu&dur=57.800&ei=SJaNW7vPBoeJ7ATTyr7YDw&ip=188.233.0.115&initcwndbps=1285000&sparams=aitags%2Cclen%2Cdur%2Cei%2Cgir%2Cid%2Cinitcwndbps%2Cip%2Cipbits%2Citag%2Ckeepalive%2Clmt%2Cmime%2Cmm%2Cmn%2Cms%2Cmv%2Cnh%2Cpl%2Crequiressl%2Csource%2Cexpire&id=o-ANAjVpGzxgf9RE3jP012pr0QnPs9IFUbYF1xmnc8NYnW', 'webm', '2018-09-03 20:15:04'),
(8, 'lecture', 'https://r2---sn-xguxaxjvh-8vbl.googlevideo.com/videoplayback?sparams=aitags%2Cclen%2Cdur%2Cei%2Cgir%2Cid%2Cinitcwndbps%2Cip%2Cipbits%2Citag%2Ckeepalive%2Clmt%2Cmime%2Cmm%2Cmn%2Cms%2Cmv%2Cnh%2Cpl%2Crequiressl%2Csource%2Cexpire&ei=SJaNW4nYJ8WX7ATvwZf4BQ&ms=au%2Crdu&itag=136&mt=1536005586&mv=m&mm=31%2C29&mn=sn-xguxaxjvh-8vbl%2Csn-n8v7znsl&keepalive=yes&clen=5597877&expire=1536027304&mime=video%2Fmp4&initcwndbps=1285000&pl=22&ip=188.233.0.115&key=yt6&lmt=1394402322904613&source=youtube&dur=57.801&nh=%2CIgpwcjAyLnN2bzA1KgkxMjcuMC4wLjE&beids=9466588&id=o-AD4oLOFkTsEIHqzKyh2zDkcfNRhAeERdOJL3T6KWqM_v&c=WEB&aitags=133%2C134%2C135%2C136%2C160%2C242%2C243%2C244%2C247%2C278&gir=yes&signature=6CC7EA64FDB6738F594265542C7B9525FC2A2761.3927E23210B4A061A36FC1E76A4970BECDD2C609&ipbits=0&requiressl=yes&fvip=14,itag=247', 'mp4', '2018-09-03 20:15:04'),
(8, 'lecture', 'https://r2---sn-xguxaxjvh-8vbl.googlevideo.com/videoplayback?sparams=aitags%2Cclen%2Cdur%2Cei%2Cgir%2Cid%2Cinitcwndbps%2Cip%2Cipbits%2Citag%2Ckeepalive%2Clmt%2Cmime%2Cmm%2Cmn%2Cms%2Cmv%2Cnh%2Cpl%2Crequiressl%2Csource%2Cexpire&ei=SJaNW4nYJ8WX7ATvwZf4BQ&ms=au%2Crdu&itag=247&mt=1536005586&mv=m&mm=31%2C29&mn=sn-xguxaxjvh-8vbl%2Csn-n8v7znsl&keepalive=yes&clen=1575306&expire=1536027304&mime=video%2Fwebm&initcwndbps=1285000&pl=22&ip=188.233.0.115&key=yt6&lmt=1435395786180088&source=youtube&dur=57.800&nh=%2CIgpwcjAyLnN2bzA1KgkxMjcuMC4wLjE&beids=9466588&id=o-AD4oLOFkTsEIHqzKyh2zDkcfNRhAeERdOJL3T6KWqM_v&c=WEB&aitags=133%2C134%2C135%2C136%2C160%2C242%2C243%2C244%2C247%2C278&gir=yes&signature=DC94B0EF294835A9DCB3059D3A34D25F7510842D.4A1341ED3ECB54CBA85C7BD576219BDAFA7A3877&ipbits=0&requiressl=yes&fvip=14,itag=135', 'webm', '2018-09-03 20:15:04');

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
  `description` text NOT NULL,
  `ts_start` timestamp NULL DEFAULT NULL,
  `ts_end` timestamp NULL DEFAULT NULL,
  `subscr_type` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `amount` decimal(6,2) UNSIGNED NOT NULL DEFAULT '0.00',
  `price_month` decimal(13,2) UNSIGNED NOT NULL,
  `price_full` decimal(13,2) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `subscription`
--

INSERT INTO `subscription` (`id`, `user`, `type`, `service`, `description`, `ts_start`, `ts_end`, `subscr_type`, `amount`, `price_month`, `price_full`) VALUES
(15, 12, 0, 16, ' 3ds Max Базовое моделирование (September 2018)', '2018-07-31 21:00:00', '2018-09-14 21:00:00', 1, '0.00', '12.00', '120.00'),
(16, 12, 0, 17, 'Визуализация в 3ds Max (September 2018)', '2018-08-31 21:00:00', '2018-09-16 21:00:00', 0, '0.00', '50.00', '350.00');

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
(1, 12, 0, '1000.00', 'add balance', '', 0, '2018-08-26 21:58:41'),
(29, 12, 1, '12.00', ' 3ds Max Базовое моделирование (September 2018)', 'group', 16, '2018-08-30 11:24:13'),
(30, 12, 1, '50.00', 'Визуализация в 3ds Max (September 2018)', 'group', 17, '2018-08-30 12:17:10'),
(31, 12, 1, '12.00', ' 3ds Max Базовое моделирование (September 2018)', 'group', 16, '2018-08-30 12:39:03');

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
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT для таблицы `files`
--
ALTER TABLE `files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
-- AUTO_INCREMENT для таблицы `subscription`
--
ALTER TABLE `subscription`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT для таблицы `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

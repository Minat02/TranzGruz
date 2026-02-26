-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Хост: localhost:3306
-- Время создания: Фев 26 2026 г., 10:00
-- Версия сервера: 5.7.24
-- Версия PHP: 8.3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `gruz`
--

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `vehicle_id` int(11) NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'В ожидании',
  `from_address` varchar(255) NOT NULL,
  `to_address` varchar(255) NOT NULL,
  `total_price` int(11) NOT NULL,
  `create_date` date NOT NULL,
  `cargo_type` varchar(100) DEFAULT NULL,
  `cargo_weight` varchar(50) DEFAULT NULL,
  `cargo_description` text,
  `cargo_volume` varchar(50) DEFAULT NULL,
  `cargo_value` int(11) DEFAULT '0',
  `contact_person` varchar(100) DEFAULT NULL,
  `contact_phone` varchar(20) DEFAULT NULL,
  `notes` text,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `orders`
--

INSERT INTO `orders` (`id`, `client_id`, `vehicle_id`, `status`, `from_address`, `to_address`, `total_price`, `create_date`, `cargo_type`, `cargo_weight`, `cargo_description`, `cargo_volume`, `cargo_value`, `contact_person`, `contact_phone`, `notes`, `updated_at`) VALUES
(2, 1, 3, 'Доставлен', 'Питер', 'Москва', 23000, '2025-12-05', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, '2025-12-15 15:40:03'),
(9, 3, 3, 'Отменен', 'Россия, Москва, Ворошилова', 'Америка, Нью-Йорк, Times Square', 52800, '2025-12-15', 'Строительные материалы', '20000', 'Перевозка материалов для строительства', '100', 100000, 'Жабн Дмитрий Иванович', '89163772300', 'Доставить вовремя', '2025-12-15 19:46:08');

-- --------------------------------------------------------

--
-- Структура таблицы `order_services`
--

CREATE TABLE `order_services` (
  `order_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `order_services`
--

INSERT INTO `order_services` (`order_id`, `service_id`) VALUES
(9, 1),
(9, 2),
(9, 3),
(9, 4),
(9, 5);

-- --------------------------------------------------------

--
-- Структура таблицы `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text,
  `price` int(11) NOT NULL,
  `is_active` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `services`
--

INSERT INTO `services` (`id`, `name`, `description`, `price`, `is_active`) VALUES
(1, 'Погрузо-разгрузочные работы', 'ымЦЫУМЦЫМыца', 1500, 1),
(2, 'Страхование груза', NULL, 500, 1),
(3, 'Упаковка', NULL, 800, 1),
(4, 'Срочная доставка', NULL, 2000, 1),
(5, 'Таможенное оформление', NULL, 3000, 1),
(6, 'Временное хранение', NULL, 1000, 1),
(7, 'долгосрочное хранение', 'drgberhbzbvzdrgze', 10000, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `created_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `full_name`, `phone`, `role`, `created_at`) VALUES
(1, 'Дмитрий', 'Minat0_Sensei', 'd9163772300@gmail.com', 'Жабин Дмитрий Иванович', '89163772300', 'admin', NULL),
(3, 'хуй', '12345', 'dima@gmail.com', 'Жабн Дмитрий Иванович', '89163772300', 'admin', NULL),
(4, 'сергей', '12345678', 'lijghbaoefg@gmail.com', NULL, NULL, 'user', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `vehicles`
--

CREATE TABLE `vehicles` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `capacity` varchar(50) NOT NULL,
  `price` int(11) NOT NULL,
  `status` enum('available','busy') DEFAULT 'available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `vehicles`
--

INSERT INTO `vehicles` (`id`, `name`, `capacity`, `price`, `status`) VALUES
(1, 'Газель', '1500', 20000, 'available'),
(2, 'Бычок', '3000', 24000, 'available'),
(3, 'Фура', '20,000 ', 45000, 'available'),
(4, 'Рефрижератор', '15,000 ', 52000, 'available'),
(7, 'Грузовик', '2000', 20000, 'available'),
(8, 'Грузовик', '2000', 20000, 'available');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_id` (`client_id`),
  ADD KEY `vehicle_id` (`vehicle_id`);

--
-- Индексы таблицы `order_services`
--
ALTER TABLE `order_services`
  ADD KEY `service_id` (`service_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Индексы таблицы `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT для таблицы `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`);

--
-- Ограничения внешнего ключа таблицы `order_services`
--
ALTER TABLE `order_services`
  ADD CONSTRAINT `order_services_ibfk_1` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`),
  ADD CONSTRAINT `order_services_ibfk_2` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

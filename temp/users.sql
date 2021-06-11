-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 11 Haz 2021, 10:07:00
-- Sunucu sürümü: 10.4.19-MariaDB
-- PHP Sürümü: 8.0.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `indicators`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `users`
--

CREATE TABLE `users` (
  `user_id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `surname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `api_key` varchar(255) DEFAULT NULL,
  `secret_key` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Tablo döküm verisi `users`
--

INSERT INTO `users` (`user_id`, `name`, `surname`, `email`, `user_name`, `password`, `api_key`, `secret_key`) VALUES
('60b27c1b55b34', 'Emre', 'Küt', 'aek@test.com', 'emre', 'e10adc3949ba59abbe56e057f20f883e', 'ad4fea364992c74775fb3f38dcf337bfc22da2304ece0fb691608ab2af966f1c8de43efd4045195a354ea2bcaadc5df0cf328a3f618280b84017dd4e907c42fa', 'ffdefd7cafea10e9a637ccd0e1e25b87b9dd92f3f1c6d36690ce62ab08934e02b3ca6db989e584fccda971c0598f8876075d47202f88760b89b6d6e1407e9c2a'),
('60c085920d52d', 'test', 'tester', 'ali@test.com', 'test', 'e10adc3949ba59abbe56e057f20f883e', '', ''),
('60c085f691920', 'test', 'test', 'test@test.com', 'tester', 'e10adc3949ba59abbe56e057f20f883e', '', '');

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

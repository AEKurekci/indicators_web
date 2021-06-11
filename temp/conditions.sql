-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 11 Haz 2021, 10:06:48
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
-- Veritabanı: `indicator_web`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `conditions`
--

CREATE TABLE `conditions` (
  `condition_id` int(11) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `parite` varchar(32) NOT NULL,
  `kar` float NOT NULL,
  `en_yuksek` float NOT NULL,
  `en_dusuk` float NOT NULL,
  `durum` tinyint(1) NOT NULL DEFAULT 1,
  `giris_tarihi` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Tablo döküm verisi `conditions`
--

INSERT INTO `conditions` (`condition_id`, `user_id`, `parite`, `kar`, `en_yuksek`, `en_dusuk`, `durum`, `giris_tarihi`) VALUES
(1, '60b27c1b55b34', 'QTUMUSDT', 0, 10.1513, -4.4993, 1, '2021-06-09 08:03:11'),
(2, '60b27c1b55b34', 'EGDLUSDT', 17.6927, 22.5155, -0.3222, 0, '2021-06-09 08:03:15'),
(3, '60b27c1b55b34', 'BTCUSDT', -1.558, 10, -3.584, 0, '2021-06-09 08:03:19'),
(4, '60b27c1b55b34', 'XMRUSDT', 10.4845, 13.1889, -0.731, 0, '2021-06-09 08:03:23'),
(5, '60b27c1b55b34', 'ETHUSDT', 3.854, 5.795, -1.845, 0, '2021-06-09 08:03:27');

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `conditions`
--
ALTER TABLE `conditions`
  ADD PRIMARY KEY (`condition_id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `conditions`
--
ALTER TABLE `conditions`
  MODIFY `condition_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

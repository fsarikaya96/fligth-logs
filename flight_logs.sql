-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Anamakine: db
-- Üretim Zamanı: 03 Ağu 2022, 16:58:46
-- Sunucu sürümü: 8.0.30
-- PHP Sürümü: 8.0.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `airline`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `flight_logs`
--

CREATE TABLE `flight_logs` (
  `id` int NOT NULL,
  `code` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_turkish_ci NOT NULL,
  `scheduled_date` datetime NOT NULL,
  `origin` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_turkish_ci NOT NULL,
  `destination` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_turkish_ci NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_turkish_ci;

--
-- Tablo döküm verisi `flight_logs`
--

INSERT INTO `flight_logs` (`id`, `code`, `scheduled_date`, `origin`, `destination`, `status`) VALUES
(1, 'PGS', '2022-08-01 13:30:00', 'IST', 'AMS', 0),
(2, 'FHY', '2022-08-02 09:00:00', 'IST', 'PAR', 1),
(3, 'FHY', '2022-07-05 12:30:00', 'IST', 'IZM', 0),
(4, 'FHY', '2022-06-15 17:00:00', 'BER', 'IST', 1),
(5, 'FHY', '2022-08-01 14:50:00', 'IST', 'BER', 1),
(6, 'FHY', '2022-08-12 11:30:00', 'IZM', 'IST', 1),
(7, 'FHY', '2022-05-25 12:30:00', 'IST', 'PAR', 0),
(8, 'FHY', '2022-07-20 10:30:00', 'TOK', 'HAM', 1),
(9, 'PGS', '2022-08-10 10:30:00', 'AMS', 'IST', 0),
(10, 'FHY', '2022-08-12 18:45:00', 'IST', 'PAR', 1),
(11, 'FHY', '2022-05-25 12:30:00', 'IST', 'BER', 0),
(12, 'FHY', '2022-07-20 19:00:00', 'TOK', 'BER', 0),
(13, 'PGS', '2022-08-02 00:30:00', 'BER', 'AMS', 1),
(14, 'PGS', '2022-11-17 04:30:00', 'AMS', 'PAR', 1),
(15, 'FHY', '2022-05-11 12:30:00', 'IST', 'BER', 0);

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `flight_logs`
--
ALTER TABLE `flight_logs`
  ADD PRIMARY KEY (`id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `flight_logs`
--
ALTER TABLE `flight_logs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 13, 2026 at 01:38 PM
-- Wersja serwera: 10.4.32-MariaDB
-- Wersja PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `casino67`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `games`
--

CREATE TABLE `games` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(255) NOT NULL,
  `type` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `games`
--

INSERT INTO `games` (`id`, `name`, `description`, `image`, `type`) VALUES
(1, 'Ruletka', 'Klasyczna ruletka kasynowa. Postaw na kolor lub liczbę i wygrywaj!', 'https://www.tapeciarnia.pl/tapety/normalne/125839_casino_ruletka.jpg', 'roulette'),
(2, 'Automaty (Slots)', 'Spróbuj swojego szczęścia w automatach. Trzy identyczne symbole to wielka wygrana!', 'https://tse1.mm.bing.net/th/id/OIP.4A66ZWVqrbaBkqGZ7TH5pwHaE8?rs=1&pid=ImgDetMain&o=7&rm=3', 'slots'),
(3, 'Rzut Monetą', 'Prosta gra 50/50. Orzeł czy reszka? Wybór należy do Ciebie.', 'https://cdn.medexpress.pl/media/images/thinkstockphotos-669541570.original.format-webp.webp', 'coinflip');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `type` enum('bet','win','deposit','daily') NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `user_id`, `amount`, `type`, `description`, `created_at`) VALUES
(1, 1, 50.00, 'daily', 'Codzienna skrzynia', '2026-03-13 10:52:12'),
(2, 1, 5000.00, 'deposit', 'Doładowanie portfela', '2026-03-13 10:52:37'),
(3, 1, 20.00, 'win', 'Wygrana w Ruletka', '2026-03-13 10:52:53'),
(4, 1, 10.00, 'bet', 'Zakład w Ruletka', '2026-03-13 10:52:59'),
(5, 1, 10.00, 'bet', 'Zakład w Ruletka', '2026-03-13 10:53:00'),
(6, 1, 20.00, 'win', 'Wygrana w Ruletka', '2026-03-13 10:53:01'),
(7, 1, 10.00, 'bet', 'Zakład w Automaty (Slots)', '2026-03-13 10:53:10'),
(8, 1, 10.00, 'bet', 'Zakład w Automaty (Slots)', '2026-03-13 10:53:12'),
(9, 1, 10.00, 'bet', 'Zakład w Rzut Monetą', '2026-03-13 10:53:20'),
(10, 1, 19.00, 'win', 'Wygrana w Rzut Monetą', '2026-03-13 10:53:29'),
(11, 1, 10.00, 'bet', 'Zakład w Rzut Monetą', '2026-03-13 10:53:33'),
(12, 1, 10.00, 'bet', 'Zakład w Rzut Monetą', '2026-03-13 11:51:24'),
(13, 1, 1.90, 'win', 'Wygrana w Rzut Monetą', '2026-03-13 12:17:11'),
(14, 1, 100.00, 'bet', 'Zakład w Rzut Monetą', '2026-03-13 12:22:49'),
(15, 1, 1000.00, 'bet', 'Zakład w Rzut Monetą', '2026-03-13 12:22:59'),
(16, 1, 4000.00, 'bet', 'Zakład w Rzut Monetą', '2026-03-13 12:23:12'),
(17, 1, 1710.00, 'win', 'Wygrana w Rzut Monetą', '2026-03-13 12:23:20'),
(18, 1, 100.00, 'bet', 'Zakład w Rzut Monetą', '2026-03-13 12:23:34'),
(19, 1, 3077.81, 'win', 'Wygrana w Rzut Monetą', '2026-03-13 12:23:46'),
(20, 1, 5847.84, 'win', 'Wygrana w Rzut Monetą', '2026-03-13 12:24:01'),
(21, 1, 11110.90, 'win', 'Wygrana w Rzut Monetą', '2026-03-13 12:24:16'),
(22, 1, 11110.90, 'bet', 'Zakład w Rzut Monetą', '2026-03-13 12:24:35');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `balance` decimal(15,2) DEFAULT 1000.00,
  `last_daily_claim` datetime DEFAULT NULL,
  `is_admin` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `balance`, `last_daily_claim`, `is_admin`, `created_at`) VALUES
(1, 'admin123', '$2y$10$i2Bt9xmSFtHo0p9Ama1Lhe2yOPf69Ppt3UyYY7qMEsn5yqGf82Vnq', 'maks@gamil.com', 0.00, '2026-03-13 11:52:12', 0, '2026-03-13 10:51:29');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `games`
--
ALTER TABLE `games`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeksy dla tabeli `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `games`
--
ALTER TABLE `games`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

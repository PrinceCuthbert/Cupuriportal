-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 29, 2025 at 05:29 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cupuri_portal`
--
CREATE DATABASE IF NOT EXISTS `cupuri_portal` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `cupuri_portal`;

-- --------------------------------------------------------

--
-- Table structure for table `downloads`
--

DROP TABLE IF EXISTS `downloads`;
CREATE TABLE `downloads` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `subject` varchar(100) NOT NULL,
  `lecture_name` varchar(100) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `downloads`
--

INSERT INTO `downloads` (`id`, `title`, `subject`, `lecture_name`, `file_path`, `uploaded_at`) VALUES
(1, 'Web Design Final', 'Web Design', 'NSHUNGUYE Justin', 'uploads/1753732800_Final.jpg', '2025-07-28 20:00:00'),
(2, 'Philosophy Final', 'Philosophy', 'Dr.Kayitare Olivier', 'uploads/1753736993_Final_May.jpg', '2025-07-28 21:09:53');

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

DROP TABLE IF EXISTS `tasks`;
CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `completed` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `user_id`, `description`, `completed`, `created_at`) VALUES
(14, 12, 'sleeping', 0, '2025-07-28 20:50:50'),
(16, 14, 'Sleeping', 0, '2025-07-28 21:10:37'),
(18, 18, 'jba', 0, '2025-07-29 15:26:56');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `contact_number` varchar(12) DEFAULT NULL,
  `address` text NOT NULL,
  `birthdate` date NOT NULL,
  `occupation` varchar(100) NOT NULL,
  `civil_status` enum('single','married','divorced','widowed') NOT NULL,
  `gender` enum('male','female','other') NOT NULL,
  `religion` varchar(50) NOT NULL,
  `bio` text DEFAULT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `registration_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `password`, `contact_number`, `address`, `birthdate`, `occupation`, `civil_status`, `gender`, `religion`, `bio`, `role`, `registration_date`, `created_at`) VALUES
(12, 'edenlisk', 'edelisk@gmail.com', '$2y$10$g8bjJmwxioKTzRE.4n75MuCpdOTV5MDqTE7LZsITujjkYosUnLuV2', '+25079869780', 'Kigali, Rwanda', '2004-11-30', 'Student', 'single', 'male', 'Adventist', 'Hello World', 'user', '2025-07-28 18:09:34', '2025-07-28 21:37:37'),
(14, 'Prince Cuthbert', 'prince@gmail.com', '$2y$10$EARcfirs.IO8bAEZdvmic.q3whtd9WP9pW5yY8saA.cdcwweFz3de', '+25079869780', 'Kigali, Rwanda', '2004-12-31', 'Student', 'single', 'male', 'Adventist', 'Software engineer', 'admin', '2025-07-28 19:43:10', '2025-07-28 21:37:37'),
(15, 'niyirema', 'niyirema@gmail.com', '$2y$10$qIhDoXjSFJmGe.DFhNuok.Nyv6DyKlINLuJINpNUgks43T8UHbWJ2', '+25079869780', 'Kigali, Rwanda', '2004-12-31', 'Student', 'single', 'female', 'Adventist', 'Lorem ipsum', 'user', '2025-07-28 21:07:20', '2025-07-28 21:37:37'),
(16, 'Molly', 'molly@yahoo.com', '$2y$10$zt9nNl0eN4Apc.0hzmDqIehbcXF2im9cyMr8AqHJzGWT/Xxs.dT6W', '+25079869780', 'Musanze, Rwanda', '1987-02-10', 'Student', 'divorced', 'female', 'Catholic', 'Lorem Ipsum', 'user', '2025-07-29 14:02:05', '2025-07-29 14:02:05'),
(17, 'Nene', 'nene@gmail.com', '$2y$10$ZkS9TY9vir3yKqP9oOyVYeiOqjWH6HOr0N..5PLx3tbMrLP/bXyqC', '+25479869780', 'Kigali, Rwanda', '2004-12-31', 'Student', 'divorced', 'female', 'Catholic', 'ewlohugfi', 'user', '2025-07-29 14:10:25', '2025-07-29 14:10:25'),
(18, 'Hello', 'hello@gmail.com', '$2y$10$fKIF9aFDMNokT/2vPvptO.sQbKC/KQ2FnI/0Q/BOI2zIXy2PjD0yi', '+25079869780', 'Kigali, Rwanda', '2004-12-31', 'Student', 'single', 'male', 'Adventist', 'asjkdaiv', 'user', '2025-07-29 15:26:26', '2025-07-29 15:26:26');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `downloads`
--
ALTER TABLE `downloads`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `downloads`
--
ALTER TABLE `downloads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

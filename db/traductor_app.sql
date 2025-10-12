-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 12-10-2025 a las 11:57:14
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `traductor_app`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hidden_texts`
--

CREATE TABLE `hidden_texts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `text_id` int(11) NOT NULL,
  `hidden_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `oxford_5000`
--

CREATE TABLE `oxford_5000` (
  `id` int(11) NOT NULL,
  `word` varchar(255) NOT NULL,
  `part_of_speech` varchar(50) DEFAULT NULL,
  `level` varchar(10) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `practice_progress`
--

CREATE TABLE `practice_progress` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `text_id` int(11) DEFAULT NULL,
  `mode` enum('selection','writing','sentences') NOT NULL,
  `total_words` int(11) NOT NULL,
  `correct_answers` int(11) NOT NULL,
  `incorrect_answers` int(11) NOT NULL,
  `accuracy` float NOT NULL,
  `session_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `completed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `practice_time`
--

CREATE TABLE `practice_time` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `mode` varchar(32) NOT NULL,
  `duration_seconds` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reading_progress`
--

CREATE TABLE `reading_progress` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `text_id` int(11) NOT NULL,
  `percent` int(11) NOT NULL DEFAULT 0,
  `pages_read` text NOT NULL,
  `updated_at` datetime NOT NULL,
  `read_count` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reading_time`
--

CREATE TABLE `reading_time` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `text_id` int(11) NOT NULL,
  `duration_seconds` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `saved_words`
--

CREATE TABLE `saved_words` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `word` text NOT NULL,
  `translation` text NOT NULL,
  `context` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `text_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `texts`
--

CREATE TABLE `texts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `title_translation` varchar(255) DEFAULT NULL,
  `content` text NOT NULL,
  `content_translation` text DEFAULT NULL,
  `is_public` tinyint(1) NOT NULL DEFAULT 0,
  `category_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `difficulty` enum('A1','A2','B1','B2','C1','C2') DEFAULT NULL,
  `difficulty_confidence` tinyint(3) UNSIGNED DEFAULT NULL,
  `difficulty_updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`

-- 🔹 Actualizar tabla users a versión segura y lista para producción
ALTER TABLE `users`
  MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT,
  ADD PRIMARY KEY (`id`),
  ADD COLUMN `imei` VARCHAR(50) DEFAULT NULL AFTER `password`,
  ADD COLUMN `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `is_admin`,
  ADD UNIQUE KEY `uniq_email` (`email`);

COMMIT;

--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `hidden_texts`
--
ALTER TABLE `hidden_texts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_text` (`user_id`,`text_id`),
  ADD KEY `text_id` (`text_id`);

--
-- Indices de la tabla `oxford_5000`
--
ALTER TABLE `oxford_5000`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_word` (`word`),
  ADD KEY `idx_level` (`level`);

--
-- Indices de la tabla `practice_progress`
--
ALTER TABLE `practice_progress`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `fk_practice_progress_text_id` (`text_id`);

--
-- Indices de la tabla `practice_time`
--
ALTER TABLE `practice_time`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indices de la tabla `reading_progress`
--
ALTER TABLE `reading_progress`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_text` (`user_id`,`text_id`);

--
-- Indices de la tabla `reading_time`
--
ALTER TABLE `reading_time`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `text_id` (`text_id`);

--
-- Indices de la tabla `saved_words`
--
ALTER TABLE `saved_words`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `fk_saved_words_text_id` (`text_id`);

--
-- Indices de la tabla `texts`
--
ALTER TABLE `texts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `fk_category` (`category_id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `hidden_texts`
--
ALTER TABLE `hidden_texts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `oxford_5000`
--
ALTER TABLE `oxford_5000`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `practice_progress`
--
ALTER TABLE `practice_progress`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `practice_time`
--
ALTER TABLE `practice_time`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `reading_progress`
--
ALTER TABLE `reading_progress`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `reading_time`
--
ALTER TABLE `reading_time`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `saved_words`
--
ALTER TABLE `saved_words`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `texts`
--
ALTER TABLE `texts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `hidden_texts`
--
ALTER TABLE `hidden_texts`
  ADD CONSTRAINT `hidden_texts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hidden_texts_ibfk_2` FOREIGN KEY (`text_id`) REFERENCES `texts` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `practice_progress`
--
ALTER TABLE `practice_progress`
  ADD CONSTRAINT `fk_practice_progress_text_id` FOREIGN KEY (`text_id`) REFERENCES `texts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `practice_progress_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `practice_time`
--
ALTER TABLE `practice_time`
  ADD CONSTRAINT `practice_time_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `reading_time`
--
ALTER TABLE `reading_time`
  ADD CONSTRAINT `reading_time_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reading_time_ibfk_2` FOREIGN KEY (`text_id`) REFERENCES `texts` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `saved_words`
--
ALTER TABLE `saved_words`
  ADD CONSTRAINT `fk_saved_words_text_id` FOREIGN KEY (`text_id`) REFERENCES `texts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `saved_words_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `texts`
--
ALTER TABLE `texts`
  ADD CONSTRAINT `fk_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `texts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

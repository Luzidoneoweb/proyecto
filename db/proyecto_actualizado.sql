-- Base de datos actualizada para el proyecto de aprendizaje de idiomas
-- Versión: 2.0
-- Fecha: 2024

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `proyecto`
--
CREATE DATABASE IF NOT EXISTS `proyecto`
CHARACTER SET utf8mb4
COLLATE utf8mb4_general_ci;

USE `proyecto`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `imei` varchar(50) DEFAULT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `last_login` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_texts`
--

CREATE TABLE IF NOT EXISTS `user_texts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `language` varchar(10) NOT NULL DEFAULT 'en',
  `difficulty` enum('beginner','intermediate','advanced') NOT NULL DEFAULT 'beginner',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `language` (`language`),
  KEY `difficulty` (`difficulty`),
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vocabulary`
--

CREATE TABLE IF NOT EXISTS `vocabulary` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `word` varchar(255) NOT NULL,
  `translation` varchar(255) NOT NULL,
  `language` varchar(10) NOT NULL DEFAULT 'en',
  `context` text DEFAULT NULL,
  `difficulty` enum('easy','medium','hard') NOT NULL DEFAULT 'easy',
  `times_practiced` int(11) NOT NULL DEFAULT 0,
  `correct_answers` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `word` (`word`),
  KEY `language` (`language`),
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_progress`
--

CREATE TABLE IF NOT EXISTS `user_progress` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `language` varchar(10) NOT NULL,
  `words_learned` int(11) NOT NULL DEFAULT 0,
  `texts_read` int(11) NOT NULL DEFAULT 0,
  `practice_sessions` int(11) NOT NULL DEFAULT 0,
  `total_time_minutes` int(11) NOT NULL DEFAULT 0,
  `streak_days` int(11) NOT NULL DEFAULT 0,
  `last_practice` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_language` (`user_id`, `language`),
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `library_texts`
--

CREATE TABLE IF NOT EXISTS `library_texts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `language` varchar(10) NOT NULL,
  `difficulty` enum('beginner','intermediate','advanced') NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `author` varchar(255) DEFAULT NULL,
  `word_count` int(11) NOT NULL DEFAULT 0,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `language` (`language`),
  KEY `difficulty` (`difficulty`),
  KEY `category` (`category`),
  KEY `is_featured` (`is_featured`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Insertar usuario administrador por defecto
--

INSERT INTO `users` (`username`, `email`, `password`, `is_admin`, `created_at`) VALUES
('admin', 'admin@proyecto.com', '$2y$10$QOSzZToM1h0NwGvRQuUprO7n3n2UFh6aAI0o8b9IkkkYduQo3awT2', 1, NOW());

-- --------------------------------------------------------

--
-- Insertar algunos textos de ejemplo en la biblioteca
--

INSERT INTO `library_texts` (`title`, `content`, `language`, `difficulty`, `category`, `author`, `word_count`, `is_featured`) VALUES
('The Future of Technology', 'In the future, children will be able to build their own prototypes with legos. Technology will become more accessible and intuitive for everyone.', 'en', 'beginner', 'Technology', 'Tech Writer', 25, 1),
('La Tecnología del Futuro', 'En el futuro, los niños podrán construir sus propios prototipos con legos. La tecnología se volverá más accesible e intuitiva para todos.', 'es', 'beginner', 'Technology', 'Tech Writer', 25, 1),
('Learning Languages', 'Learning a new language opens doors to new cultures and opportunities. It is one of the most rewarding experiences you can have.', 'en', 'intermediate', 'Education', 'Language Expert', 24, 1),
('Aprender Idiomas', 'Aprender un nuevo idioma abre puertas a nuevas culturas y oportunidades. Es una de las experiencias más gratificantes que puedes tener.', 'es', 'intermediate', 'Education', 'Language Expert', 24, 1);

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- --------------------------------------------------------
-- Хост:                         127.0.0.1
-- Версия сервера:               8.0.30 - MySQL Community Server - GPL
-- Операционная система:         Win64
-- HeidiSQL Версия:              12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Дамп структуры базы данных my_world
DROP DATABASE IF EXISTS `my_world`;
CREATE DATABASE IF NOT EXISTS `my_world` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `my_world`;

-- Дамп структуры для таблица my_world.classes
DROP TABLE IF EXISTS `classes`;
CREATE TABLE IF NOT EXISTS `classes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Дамп данных таблицы my_world.classes: ~14 rows (приблизительно)
REPLACE INTO `classes` (`id`, `name`, `created_at`, `updated_at`) VALUES
	(1, 'АВА', '2023-12-22 02:12:36', '2023-12-22 02:12:36'),
	(2, 'Дефектолог', '2023-12-22 02:12:36', '2023-12-22 02:12:36'),
	(3, 'Логопед', '2023-12-22 02:12:36', '2023-12-22 02:12:36'),
	(4, 'АФК', '2023-12-22 02:12:36', '2023-12-22 02:12:36'),
	(5, 'СИ', '2023-12-22 02:12:36', '2023-12-22 02:12:36'),
	(6, 'МС', '2023-12-22 02:12:36', '2023-12-22 02:12:36'),
	(7, 'Денвер', '2023-12-22 02:12:36', '2023-12-22 02:12:36'),
	(8, 'Групповые занятия', '2023-12-22 02:12:36', '2023-12-22 02:12:36'),
	(9, 'Каллиграфия', '2023-12-22 02:12:36', '2023-12-22 02:12:36'),
	(10, 'Печатание', '2023-12-22 02:12:36', '2023-12-22 02:12:36'),
	(11, 'Психолог', '2023-12-22 02:12:36', '2023-12-22 02:12:36'),
	(12, 'Нейропсихолог', '2023-12-22 02:12:36', '2023-12-22 02:12:36'),
	(13, 'Школа', '2023-12-22 02:12:36', '2023-12-22 02:12:36'),
	(14, 'Прочие', '2023-12-22 02:12:36', '2023-12-22 02:12:36');

-- Дамп структуры для таблица my_world.clients
DROP TABLE IF EXISTS `clients`;
CREATE TABLE IF NOT EXISTS `clients` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `fio` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `burndate` date NOT NULL,
  `diagnos` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `contras` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `mother` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mother_phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `father` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `father_phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `adress` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Дамп данных таблицы my_world.clients: ~12 rows (приблизительно)
REPLACE INTO `clients` (`id`, `fio`, `burndate`, `diagnos`, `contras`, `mother`, `mother_phone`, `father`, `father_phone`, `adress`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
	(3, 'Титов А.К.', '2020-10-20', 'Диагноз 3', 'Противопоказания 3', 'Степанова В.В.', '+79222222222', '-', '-', '-', 1, 1, NULL, '2023-10-13 04:58:50'),
	(4, 'Иванов Иван Иванович', '2023-10-12', 'Диагноз 5', 'Противопоказания 5', 'Иванова Анна Ивановна', '-', 'Иванов Петр Петрович', '-', '-', 1, 1, NULL, NULL),
	(5, 'Петров Петр', '2008-11-20', 'Д 3', 'П 3', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-11-08 11:39:33', '2023-11-08 11:39:33'),
	(6, 'Стеклов Алекс', '2007-11-20', 'Д 4', 'П 4', 'Стеклова М.', '+7(777) 777 77 77', 'Стеклов С.', '+7(333) 333 33 33', 'Саратов', NULL, NULL, '2023-11-08 11:54:13', '2023-11-08 11:54:13'),
	(7, 'Гвоздюк Алексей', '2007-11-20', 'Д5', 'П5', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-11-08 12:22:49', '2023-11-08 12:22:49'),
	(8, 'Сидоров Ярослав', '2007-11-20', 'Д10', 'П10', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-11-08 13:54:21', '2023-11-08 13:54:21'),
	(9, 'Сидорова Алина', '2008-11-20', 'Д7', 'П7', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-11-09 11:49:34', '2023-11-09 11:49:34'),
	(10, 'Сидоров Сидор', '2015-11-20', 'Д7', 'П7', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-11-15 06:04:48', '2023-11-15 06:04:48'),
	(11, 'Александров Александр', '2015-11-20', 'Д11', 'П11', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-11-15 06:10:38', '2023-11-15 06:10:38'),
	(13, 'Максимов Максим', '2015-11-20', 'Д11', 'П11', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-11-15 06:39:51', '2023-11-15 06:39:51'),
	(14, 'Андреев Андрей', '2015-11-20', 'Д11', 'П11', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-11-15 06:43:55', '2023-11-15 06:43:55'),
	(15, 'п', '2015-11-20', 'п', 'п', 'п', '+7(111) 111 11 11', 'п', '+7(333) 333 33 33', 'п', NULL, NULL, '2023-11-15 06:50:38', '2023-11-15 06:50:38');

-- Дамп структуры для таблица my_world.failed_jobs
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Дамп данных таблицы my_world.failed_jobs: ~0 rows (приблизительно)

-- Дамп структуры для таблица my_world.migrations
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Дамп данных таблицы my_world.migrations: ~10 rows (приблизительно)
REPLACE INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '2014_10_12_000000_create_users_table', 1),
	(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
	(3, '2019_08_19_000000_create_failed_jobs_table', 1),
	(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
	(5, '2023_12_18_093014_create_permission_tables', 1),
	(6, '2023_12_21_063016_add_username_field_to_users_table', 1),
	(7, '2023_12_21_065018_add_specialist_phone_fields_to_users_table', 2),
	(8, '2023_12_21_123945_add_clients_table', 3),
	(9, '2023_12_22_045536_add_classes_table', 4),
	(10, '2023_12_22_045557_add_wishes_table', 4);

-- Дамп структуры для таблица my_world.model_has_permissions
DROP TABLE IF EXISTS `model_has_permissions`;
CREATE TABLE IF NOT EXISTS `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Дамп данных таблицы my_world.model_has_permissions: ~0 rows (приблизительно)

-- Дамп структуры для таблица my_world.model_has_roles
DROP TABLE IF EXISTS `model_has_roles`;
CREATE TABLE IF NOT EXISTS `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Дамп данных таблицы my_world.model_has_roles: ~4 rows (приблизительно)
REPLACE INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
	(1, 'App\\Models\\User', 1),
	(2, 'App\\Models\\User', 2),
	(3, 'App\\Models\\User', 3),
	(3, 'App\\Models\\User', 20);

-- Дамп структуры для таблица my_world.password_reset_tokens
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Дамп данных таблицы my_world.password_reset_tokens: ~0 rows (приблизительно)

-- Дамп структуры для таблица my_world.permissions
DROP TABLE IF EXISTS `permissions`;
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Дамп данных таблицы my_world.permissions: ~0 rows (приблизительно)

-- Дамп структуры для таблица my_world.personal_access_tokens
DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Дамп данных таблицы my_world.personal_access_tokens: ~0 rows (приблизительно)

-- Дамп структуры для таблица my_world.roles
DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Дамп данных таблицы my_world.roles: ~3 rows (приблизительно)
REPLACE INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
	(1, 'admin', 'web', '2023-12-18 07:12:03', '2023-12-18 07:12:03'),
	(2, 'moderator', 'web', '2023-12-18 07:12:03', '2023-12-18 07:12:03'),
	(3, 'user', 'web', '2023-12-18 07:12:03', '2023-12-18 07:12:03');

-- Дамп структуры для таблица my_world.role_has_permissions
DROP TABLE IF EXISTS `role_has_permissions`;
CREATE TABLE IF NOT EXISTS `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Дамп данных таблицы my_world.role_has_permissions: ~0 rows (приблизительно)

-- Дамп структуры для таблица my_world.users
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `specialist` int NOT NULL DEFAULT '0',
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_username_unique` (`username`) USING BTREE,
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Дамп данных таблицы my_world.users: ~4 rows (приблизительно)
REPLACE INTO `users` (`id`, `name`, `email`, `username`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `specialist`, `phone`) VALUES
	(1, 'Иванов Иван Иванович', 'admin@example.com', 'Administrator', '2023-12-18 07:12:03', '$2y$12$SpXHbFldExHdn7K/gtAUl.88JvoBVNARiC63NbCkN9khz./fzKKpO', 'gKyD6g1ssiQ65yBrjAgMVrRAmMK22Q8tukrgYzkCdfl8kV6es4E9bYALClHU', '2023-12-18 07:12:03', '2023-12-21 02:48:06', 0, NULL),
	(2, 'Петрова Галина Марковна', 'moderator@example.com', 'Moderator', '2023-12-18 07:12:03', '$2y$12$/Z4Z6PlPZDIzm8zVRoXgJOi0YU7EEeEs/YUMj/KBJEcaUC0EjHa3W', 'udAMyxYoAD', '2023-12-18 07:12:03', '2023-12-18 07:12:03', 0, NULL),
	(3, 'Голушко Ирина Петровна', 'user@example.com', 'User', '2023-12-18 07:12:03', '$2y$12$0ugHNcvxdvTGFWsW7xO5XONAKHGUW/GTxRO/AI8RFVn2x2lf.o/a6', 'cS0kRwko0fcTxDxGsCWN0V7qez1TPvYsBVvF7Sl7fG3E4JmEc3LUmjdM2w9M', '2023-12-18 07:12:03', '2023-12-18 07:12:03', 1, NULL),
	(20, 'Хоста Екатерина Михайловна', NULL, 'hostaem', NULL, '$2y$12$8jGc7iP.z2rozVZ2/iu3f.paLL9C9iS2Jj6cnLvI64bJm9m12eXHi', NULL, '2023-12-21 09:12:46', '2023-12-21 09:12:46', 1, '+7(777)-777-77-77');

-- Дамп структуры для таблица my_world.wishes
DROP TABLE IF EXISTS `wishes`;
CREATE TABLE IF NOT EXISTS `wishes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `class_id` int NOT NULL,
  `client_id` int NOT NULL,
  `prefer_amount_of_classes` int NOT NULL,
  `prefer_time` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Дамп данных таблицы my_world.wishes: ~0 rows (приблизительно)

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;

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
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Дамп данных таблицы my_world.classes: ~14 rows (приблизительно)
DELETE FROM `classes`;
INSERT INTO `classes` (`id`, `name`, `created_at`, `updated_at`) VALUES
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
  `fio` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `burndate` date NOT NULL,
  `diagnos` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `contras` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Дамп данных таблицы my_world.clients: ~15 rows (приблизительно)
DELETE FROM `clients`;
INSERT INTO `clients` (`id`, `fio`, `burndate`, `diagnos`, `contras`, `mother`, `mother_phone`, `father`, `father_phone`, `adress`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
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
	(15, 'п', '2015-11-20', 'п', 'п', 'п', '+7(111) 111 11 11', 'п', '+7(333) 333 33 33', 'п', NULL, NULL, '2023-11-15 06:50:38', '2023-11-15 06:50:38'),
	(16, 'Петров Василий Иванович', '1975-05-17', 'Quos reiciendis duci', 'Sed incididunt omnis', 'Odit placeat in ame', '+7(138)-285-65-16', 'Facilis suscipit ani', '+7(119)-263-37-60', 'Exercitationem place', 1, 1, '2023-12-30 01:39:51', '2024-01-05 05:49:46'),
	(17, 'Sed placeat nesciun', '1993-09-06', 'Voluptas voluptatibu', 'Deserunt non laboris', 'Laboris et non volup', '+7(178)-211-56-93', 'Eu quia aut quia tot', '+7(167)-724-42-12', 'Magnam sint volupta', 1, 1, '2023-12-30 07:10:25', '2023-12-30 07:10:25'),
	(18, 'Delectus exercitati', '1987-06-19', 'Magna nemo deserunt', 'Ipsam reprehenderit', 'Enim rerum iusto nem', '+7(151)-655-25-95', 'Delectus similique', '+7(190)-728-71-08', 'Sit laboris voluptat', 1, 1, '2023-12-30 07:11:07', '2023-12-30 07:11:07'),
	(19, 'Voluptas blanditiis', '2005-10-17', 'Expedita sed libero', 'Vero adipisicing sun', 'Saepe recusandae Se', '+7(190)-984-56-47', 'Id autem officiis fu', '+7(141)-251-19-07', 'Neque totam mollit e', 1, 1, '2023-12-30 07:12:06', '2023-12-30 07:12:06');

-- Дамп структуры для таблица my_world.failed_jobs
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Дамп данных таблицы my_world.failed_jobs: ~0 rows (приблизительно)
DELETE FROM `failed_jobs`;

-- Дамп структуры для таблица my_world.migrations
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Дамп данных таблицы my_world.migrations: ~14 rows (приблизительно)
DELETE FROM `migrations`;
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '2014_10_12_000000_create_users_table', 1),
	(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
	(3, '2019_08_19_000000_create_failed_jobs_table', 1),
	(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
	(5, '2023_12_18_093014_create_permission_tables', 1),
	(6, '2023_12_21_063016_add_username_field_to_users_table', 1),
	(7, '2023_12_21_065018_add_specialist_phone_fields_to_users_table', 2),
	(8, '2023_12_21_123945_add_clients_table', 3),
	(9, '2023_12_22_045536_add_classes_table', 4),
	(10, '2023_12_22_045557_add_wishes_table', 4),
	(11, '2023_12_27_163656_create_time_ranges_table', 5),
	(12, '2024_01_01_065943_create_task_table', 6),
	(13, '2024_01_01_160150_create_record_table', 7),
	(14, '2024_01_01_171417_create_rooms_table', 8);

-- Дамп структуры для таблица my_world.model_has_permissions
DROP TABLE IF EXISTS `model_has_permissions`;
CREATE TABLE IF NOT EXISTS `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Дамп данных таблицы my_world.model_has_permissions: ~0 rows (приблизительно)
DELETE FROM `model_has_permissions`;

-- Дамп структуры для таблица my_world.model_has_roles
DROP TABLE IF EXISTS `model_has_roles`;
CREATE TABLE IF NOT EXISTS `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Дамп данных таблицы my_world.model_has_roles: ~6 rows (приблизительно)
DELETE FROM `model_has_roles`;
INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
	(1, 'App\\Models\\User', 1),
	(2, 'App\\Models\\User', 2),
	(3, 'App\\Models\\User', 3),
	(3, 'App\\Models\\User', 20),
	(3, 'App\\Models\\User', 21),
	(3, 'App\\Models\\User', 22);

-- Дамп структуры для таблица my_world.password_reset_tokens
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Дамп данных таблицы my_world.password_reset_tokens: ~0 rows (приблизительно)
DELETE FROM `password_reset_tokens`;

-- Дамп структуры для таблица my_world.permissions
DROP TABLE IF EXISTS `permissions`;
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Дамп данных таблицы my_world.permissions: ~0 rows (приблизительно)
DELETE FROM `permissions`;

-- Дамп структуры для таблица my_world.personal_access_tokens
DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Дамп данных таблицы my_world.personal_access_tokens: ~0 rows (приблизительно)
DELETE FROM `personal_access_tokens`;

-- Дамп структуры для таблица my_world.record
DROP TABLE IF EXISTS `record`;
CREATE TABLE IF NOT EXISTS `record` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `educationDate` date NOT NULL,
  `time_range` int NOT NULL,
  `user_id` int NOT NULL,
  `client_id` int NOT NULL,
  `class_id` int NOT NULL,
  `room_id` int NOT NULL,
  `is_present` int NOT NULL DEFAULT '0',
  `created_by` int NOT NULL,
  `updated_by` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Дамп данных таблицы my_world.record: ~6 rows (приблизительно)
DELETE FROM `record`;
INSERT INTO `record` (`id`, `educationDate`, `time_range`, `user_id`, `client_id`, `class_id`, `room_id`, `is_present`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
	(1, '2024-01-07', 4, 3, 3, 12, 2, 0, 1, 1, '2024-01-04 02:21:01', '2024-01-05 05:44:09'),
	(2, '2024-01-07', 3, 20, 4, 12, 1, 1, 1, 1, '2024-01-04 03:44:14', '2024-01-05 05:18:50'),
	(3, '2024-01-07', 3, 3, 7, 10, 2, 0, 1, 1, '2024-01-05 05:44:44', '2024-01-05 05:44:44'),
	(4, '2024-01-07', 11, 20, 10, 9, 1, 0, 1, 1, '2024-01-05 05:46:45', '2024-01-05 05:46:45'),
	(5, '2024-01-07', 1, 21, 16, 10, 2, 0, 1, 1, '2024-01-05 05:54:20', '2024-01-05 05:54:20'),
	(6, '2024-01-07', 4, 21, 16, 13, 3, 0, 1, 1, '2024-01-05 06:04:55', '2024-01-05 06:04:55'),
	(7, '2024-01-07', 2, 22, 9, 8, 1, 0, 1, 1, '2024-01-05 07:09:28', '2024-01-05 07:09:28');

-- Дамп структуры для таблица my_world.roles
DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Дамп данных таблицы my_world.roles: ~3 rows (приблизительно)
DELETE FROM `roles`;
INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
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
DELETE FROM `role_has_permissions`;

-- Дамп структуры для таблица my_world.rooms
DROP TABLE IF EXISTS `rooms`;
CREATE TABLE IF NOT EXISTS `rooms` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Дамп данных таблицы my_world.rooms: ~2 rows (приблизительно)
DELETE FROM `rooms`;
INSERT INTO `rooms` (`id`, `name`, `created_at`, `updated_at`) VALUES
	(1, 'Кабинет логопеда', '2024-01-01 14:32:02', '2024-01-01 14:32:02'),
	(2, 'Кабинет дефектолога', '2024-01-01 14:32:02', '2024-01-01 14:32:02'),
	(3, 'Кабинет психолога', '2024-01-01 14:32:02', '2024-01-01 14:32:02');

-- Дамп структуры для таблица my_world.task
DROP TABLE IF EXISTS `task`;
CREATE TABLE IF NOT EXISTS `task` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `task` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `executeDate` date NOT NULL,
  `executed` int NOT NULL DEFAULT '0',
  `comments` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `created_by` int NOT NULL,
  `updated_by` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Дамп данных таблицы my_world.task: ~1 rows (приблизительно)
DELETE FROM `task`;
INSERT INTO `task` (`id`, `task`, `executeDate`, `executed`, `comments`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
	(1, 'Подготовить карточки ПЕКС «Много» «Мало» для Корчагина Артема', '2023-01-03', 0, '', 1, 1, '2024-01-01 04:21:30', '2024-01-01 04:21:30');

-- Дамп структуры для таблица my_world.time_ranges
DROP TABLE IF EXISTS `time_ranges`;
CREATE TABLE IF NOT EXISTS `time_ranges` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Дамп данных таблицы my_world.time_ranges: ~16 rows (приблизительно)
DELETE FROM `time_ranges`;
INSERT INTO `time_ranges` (`id`, `name`, `created_at`, `updated_at`) VALUES
	(1, '8:00-8:45', '2023-12-27 13:46:29', '2023-12-27 13:46:29'),
	(2, '8:45-9:30', '2023-12-27 13:46:29', '2023-12-27 13:46:29'),
	(3, '9:30-10:15', '2023-12-27 13:46:29', '2023-12-27 13:46:29'),
	(4, '10:15-11:00', '2023-12-27 13:46:29', '2023-12-27 13:46:29'),
	(5, '11:00-11:45', '2023-12-27 13:46:29', '2023-12-27 13:46:29'),
	(6, '11:45-12:30', '2023-12-27 13:46:29', '2023-12-27 13:46:29'),
	(7, '12:30-13:15', '2023-12-27 13:46:29', '2023-12-27 13:46:29'),
	(8, '13:15-14:00', '2023-12-27 13:46:29', '2023-12-27 13:46:29'),
	(9, '14:00-14:45', '2023-12-27 13:46:29', '2023-12-27 13:46:29'),
	(10, '14:45-15:30', '2023-12-27 13:46:29', '2023-12-27 13:46:29'),
	(11, '15:30-16:15', '2023-12-27 13:46:29', '2023-12-27 13:46:29'),
	(12, '16:15-17:00', '2023-12-27 13:46:29', '2023-12-27 13:46:29'),
	(13, '17:00-17:45', '2023-12-27 13:46:29', '2023-12-27 13:46:29'),
	(14, '17:45-18:30', '2023-12-27 13:46:29', '2023-12-27 13:46:29'),
	(15, '18:30-19:15', '2023-12-27 13:46:29', '2023-12-27 13:46:29'),
	(16, '19:15-20:00', '2023-12-27 13:46:29', '2023-12-27 13:46:29');

-- Дамп структуры для таблица my_world.users
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `specialist` int NOT NULL DEFAULT '0',
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_username_unique` (`username`) USING BTREE,
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Дамп данных таблицы my_world.users: ~6 rows (приблизительно)
DELETE FROM `users`;
INSERT INTO `users` (`id`, `name`, `email`, `username`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `specialist`, `phone`) VALUES
	(1, 'Иванов Иван Иванович', 'admin@example.com', 'Administrator', '2023-12-18 07:12:03', '$2y$12$SpXHbFldExHdn7K/gtAUl.88JvoBVNARiC63NbCkN9khz./fzKKpO', '5hiCZZ82FbdQ2SRnGIJWXCvEMaCov10UsGw5jOroaUhzmdg8wR7cLNd2sH9z', '2023-12-18 07:12:03', '2023-12-21 02:48:06', 0, NULL),
	(2, 'Петрова Галина Марковна', 'moderator@example.com', 'Moderator', '2023-12-18 07:12:03', '$2y$12$WQBC/zeOqe13xRIArmuUtuJ8PJjLvsZBMSBz/XhPDu0laJO8fxuXq', 'Qmh2GBv0imoEaenWOdrYl0nleTWRpymkxkP3rrvVLMtsFAHQlIQI2NYfuZtY', '2023-12-18 07:12:03', '2024-01-01 10:59:53', 0, '+7(333)-333-33-33'),
	(3, 'Голушко Ирина Петровна', 'user@example.com', 'User', '2023-12-18 07:12:03', '$2y$12$PQvju6QoVj.sGlrph3G/AO6mE839222DXiWbZOViImRYfo7YzieHy', 'Cw71JSFJxL8KuzXIRXuoZYByd7jPz2Ca1rmbOiIA4AycPTlDyAXdb0IqhbZw', '2023-12-18 07:12:03', '2024-01-01 10:49:36', 1, '+7(777)-777-77-77'),
	(20, 'Хоста Екатерина Михайловна', NULL, 'hostaem', NULL, '$2y$12$8jGc7iP.z2rozVZ2/iu3f.paLL9C9iS2Jj6cnLvI64bJm9m12eXHi', NULL, '2023-12-21 09:12:46', '2023-12-21 09:12:46', 1, '+7(777)-777-77-77'),
	(21, 'Саратов Ирина Михайловна', NULL, 'user2', NULL, '$2y$12$2RgrBpXHROBnLe6rRhPxdO6ulXktfp4TGV2MEzhtcgozAgmX3ORv2', NULL, '2024-01-05 05:48:51', '2024-01-05 05:48:51', 1, '+7(896)-786-56-56'),
	(22, 'Самара Светлана Ивановна', NULL, 'user3', NULL, '$2y$12$Faatdb7n9hNONlkrqBCPTO0/S617CJNzeG7g1HFnL4FcSTLdyjZJO', NULL, '2024-01-05 07:08:54', '2024-01-05 07:09:03', 1, '+7(231)-132-13-21');

-- Дамп структуры для таблица my_world.wishes
DROP TABLE IF EXISTS `wishes`;
CREATE TABLE IF NOT EXISTS `wishes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `class_id` int NOT NULL,
  `client_id` int NOT NULL,
  `prefer_amount_of_classes` int NOT NULL,
  `prefer_time_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Дамп данных таблицы my_world.wishes: ~6 rows (приблизительно)
DELETE FROM `wishes`;
INSERT INTO `wishes` (`id`, `class_id`, `client_id`, `prefer_amount_of_classes`, `prefer_time_id`, `created_at`, `updated_at`) VALUES
	(16, 5, 19, 1, 4, '2023-12-30 07:12:06', '2023-12-30 07:12:06'),
	(17, 5, 19, 3, 5, '2023-12-30 07:12:06', '2023-12-30 07:12:06'),
	(18, 1, 16, 1, 6, '2024-01-05 05:49:46', '2024-01-05 05:49:46'),
	(19, 4, 16, 3, 6, '2024-01-05 05:49:46', '2024-01-05 05:49:46'),
	(20, 8, 16, 5, 11, '2024-01-05 05:49:46', '2024-01-05 05:49:46'),
	(21, 13, 16, 7, 15, '2024-01-05 05:49:46', '2024-01-05 05:49:46');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;

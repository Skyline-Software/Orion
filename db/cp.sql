-- --------------------------------------------------------
-- Хост:                         192.168.83.137
-- Версия сервера:               5.7.23-0ubuntu0.16.04.1 - (Ubuntu)
-- Операционная система:         Linux
-- HeidiSQL Версия:              9.5.0.5196
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;







-- Дамп структуры для таблица card.profile
CREATE TABLE IF NOT EXISTS `profile` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `type` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_users_customer` (`user_id`),
  CONSTRAINT `fk_users_customer` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8;

-- Дамп данных таблицы card.profile: ~2 rows (приблизительно)
/*!40000 ALTER TABLE `profile` DISABLE KEYS */;
INSERT INTO `profile` (`id`, `user_id`, `name`, `phone`, `address`, `type`) VALUES
	(17, 1, 'asd', 'asd', 'asd2333', 1),
	(31, 34, '', '', '', 1),
	(32, 35, 'Василий Доставкин', '', '', 0),
	(33, 36, 'asd', '', '', 0);
/*!40000 ALTER TABLE `profile` ENABLE KEYS */;

-- Дамп структуры для таблица card.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `auth_key` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `password_hash` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` int(11) DEFAULT '0',
  `password_reset_token` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `email_confirm_token` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` smallint(6) NOT NULL DEFAULT '10',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `typography_id` int(11) DEFAULT NULL,
  `new` int(11) DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `password_reset_token` (`password_reset_token`),
  UNIQUE KEY `email_confirm_token` (`email_confirm_token`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8;

-- Дамп данных таблицы card.users: ~2 rows (приблизительно)
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `auth_key`, `password_hash`, `type`, `password_reset_token`, `email`, `email_confirm_token`, `status`, `created_at`, `updated_at`, `typography_id`, `new`) VALUES
	(1, 'xu_Vt1-upfzG2_dtJYQkjgOA6PuMCm0h', '$2y$13$ki28UTqNLh.oTejRO1J7tuUKVtNCSQYaPLISOEJta6Ja9lbXtAE2q', 2, NULL, 'asd@asd.ru', NULL, 10, 1533322493, 1537190283, 3, 1),
	(34, '88arehZ65kmMVG3AvJvMxW_OhJ-ZmNk0', '$2y$13$ca7hyGtAT1lMHmMZgDqBu.A/63kOwQewnG86p8MlEu8XefYG/xkem', 0, NULL, 'gromak.f@gmail.com', NULL, 10, 1537259542, 1537276358, NULL, 1),
	(35, 'dIg5v-mIkC_J6-WZNnlSg_r0TY0V9fpL', '$2y$13$eydYXPZbW5e8btyExmTcdO3G/uc6sJVxfhcyP5fMDdab4TffOCxVW', 3, NULL, 'courier@asd.ru', NULL, 10, 1537275768, 1537275768, NULL, 1),
	(36, 'hIF5bjXXbdF9ZkxEEW2VSyO9ruhLBJCc', '$2y$13$exzuM.j7d.8upTVAMMTjEOl7.tiMSWBkZGxBCl8m/ocjYrF8P.atG', 5, NULL, 'owner@asd.ru', NULL, 10, 1537276982, 1537276982, NULL, 1);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

-- Дамп структуры для таблица card.user_networks
CREATE TABLE IF NOT EXISTS `user_networks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `identity` varchar(255) NOT NULL,
  `network` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx-user_networks-identity-name` (`identity`,`network`),
  KEY `idx-user_networks-user_id` (`user_id`),
  CONSTRAINT `fk-user_networks-user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- Дамп данных таблицы card.user_networks: ~0 rows (приблизительно)
/*!40000 ALTER TABLE `user_networks` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_networks` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;

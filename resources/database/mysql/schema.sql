CREATE TABLE IF NOT EXISTS `author` (
  `id` VARCHAR(255) NOT NULL,
  `full_name` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`))
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;

CREATE TABLE IF NOT EXISTS `quote` (
  `id` CHAR(36) NOT NULL,
  `author_id` VARCHAR(255) NOT NULL,
  `quote` VARCHAR(1000) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_quote_author_id_idx` (`author_id` ASC) VISIBLE,
  CONSTRAINT `fk_quote_author_id`
    FOREIGN KEY (`author_id`)
    REFERENCES `author` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;

CREATE TABLE IF NOT EXISTS `event_store` (
  `id` char(36) COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `aggregate_id` varchar(255) COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `version` mediumint(8) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `payload` json NOT NULL,
  `priority` tinyint(1) NOT NULL,
  `is_publishable` tinyint(1) NOT NULL,
  `occurred_on` datetime(6) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `core_store_name_idx` (`name`),
  KEY `core_store_name_aggregate_idx` (`aggregate_id`,`name`),
  KEY `core_store_name_occurred_idx` (`name`,`occurred_on`),
  KEY `core_store_occurred_idx` (`occurred_on`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE IF NOT EXISTS `event_not_published` (
  `event_id` char(36) COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `last_error` json NULL DEFAULT NULL,
  `created_at` datetime(6) NOT NULL,
  `updated_at` datetime(6) NOT NULL,
  PRIMARY KEY (`event_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE IF NOT EXISTS `event_failed` (
  `event_id` char(36) COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `retry` tinyint(1) NOT NULL,
  `created_at` datetime(6) NOT NULL,
  `last_error` json NOT NULL,
  PRIMARY KEY (`event_id`),
  KEY `core_event_failed_name_idx` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
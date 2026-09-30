-- Tenant-scoped email notification settings and send log (works on MariaDB and MySQL 8)

-- user_id = 0 means the tenant-wide default; a user's own row overrides it (opt-out/in)
CREATE TABLE IF NOT EXISTS `notification_settings` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `tenant_id` INT(11) NOT NULL,
  `user_id` INT(11) NOT NULL DEFAULT 0,
  `category` VARCHAR(50) NOT NULL,
  `enabled` TINYINT(1) NOT NULL DEFAULT 1,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_tenant_user_cat` (`tenant_id`, `user_id`, `category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Every notification email attempt; tenant_id NULL = platform-level mail
CREATE TABLE IF NOT EXISTS `email_log` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `tenant_id` INT(11) DEFAULT NULL,
  `user_id` INT(11) DEFAULT NULL,
  `category` VARCHAR(50) NOT NULL,
  `recipient` VARCHAR(255) NOT NULL,
  `subject` VARCHAR(255) NOT NULL,
  `status` VARCHAR(20) NOT NULL DEFAULT 'sent',
  `error` VARCHAR(255) DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_email_log_tenant` (`tenant_id`, `created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

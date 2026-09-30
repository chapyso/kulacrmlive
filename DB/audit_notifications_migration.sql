-- Audit log and in-app notifications tables (idempotent; MariaDB and MySQL 8)
-- Production had neither table, so log_audit() recorded nothing and notification widgets failed.

CREATE TABLE IF NOT EXISTS `audit_logs` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `tenant_id` INT(11) DEFAULT NULL,
  `user_id` INT(11) DEFAULT NULL,
  `user_email` VARCHAR(255) DEFAULT NULL,
  `action` VARCHAR(100) NOT NULL,
  `target_tenant_id` INT(11) DEFAULT NULL,
  `ip_address` VARCHAR(64) DEFAULT NULL,
  `details` TEXT DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_audit_tenant` (`tenant_id`, `created_at`),
  KEY `idx_audit_action` (`action`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `notifications` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `tenant_id` INT(11) NOT NULL,
  `user_id` INT(11) DEFAULT NULL,
  `type` VARCHAR(50) NOT NULL DEFAULT 'info',
  `category` VARCHAR(50) DEFAULT NULL,
  `title` VARCHAR(255) NOT NULL,
  `message` TEXT DEFAULT NULL,
  `icon` VARCHAR(80) DEFAULT NULL,
  `icon_bg` VARCHAR(30) DEFAULT NULL,
  `icon_color` VARCHAR(30) DEFAULT NULL,
  `link` VARCHAR(255) DEFAULT NULL,
  `channel` VARCHAR(50) DEFAULT NULL,
  `priority` VARCHAR(20) DEFAULT 'info',
  `sent_by` VARCHAR(100) DEFAULT NULL,
  `is_read` TINYINT(1) NOT NULL DEFAULT 0,
  `read_at` DATETIME DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_notif_tenant` (`tenant_id`, `is_read`, `created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- KulaAI Super Admin Subscription Plan & AI Settings Migration

ALTER TABLE `subscription_plans` ADD COLUMN IF NOT EXISTS `has_ai_access` TINYINT(1) NOT NULL DEFAULT 1;

-- Set Default AI Access per Tier
UPDATE `subscription_plans` SET `has_ai_access` = 0 WHERE `code` IN ('free', 'starter');
UPDATE `subscription_plans` SET `has_ai_access` = 1 WHERE `code` IN ('pro', 'enterprise');

-- Global AI Settings Table
CREATE TABLE IF NOT EXISTS `ai_global_settings` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `ai_enabled` TINYINT(1) NOT NULL DEFAULT 1,
  `default_provider` VARCHAR(50) NOT NULL DEFAULT 'gemini',
  `api_key` TEXT DEFAULT NULL,
  `model_name` VARCHAR(100) DEFAULT 'gemini-1.5-flash',
  `allow_tenant_custom_keys` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seed default setting record
INSERT INTO `ai_global_settings` (`id`, `ai_enabled`, `default_provider`, `model_name`, `allow_tenant_custom_keys`)
VALUES (1, 1, 'gemini', 'gemini-1.5-flash', 0)
ON DUPLICATE KEY UPDATE `ai_enabled` = VALUES(`ai_enabled`);

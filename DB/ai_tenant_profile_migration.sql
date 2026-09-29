-- Per-tenant KulaAI persona so the assistant is not hardwired to one business type (idempotent)
CREATE TABLE IF NOT EXISTS `ai_tenant_profile` (
  `tenant_id` INT(11) NOT NULL,
  `assistant_name` VARCHAR(100) NOT NULL DEFAULT 'KulaAI',
  `business_type` VARCHAR(150) NOT NULL DEFAULT 'livestock farm',
  `terminology` TEXT DEFAULT NULL,      -- e.g. "batch=flock; shed=house"
  `persona_notes` TEXT DEFAULT NULL,    -- extra instructions appended to the system prompt
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`tenant_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Plan gate used by Kula_ai::check_plan_ai_access (was missing from all migrations)
ALTER TABLE `subscription_plans` ADD COLUMN IF NOT EXISTS `has_ai_access` TINYINT(1) NOT NULL DEFAULT 0;
UPDATE `subscription_plans` SET `has_ai_access` = 1 WHERE `code` IN ('pro', 'enterprise') AND `has_ai_access` = 0;

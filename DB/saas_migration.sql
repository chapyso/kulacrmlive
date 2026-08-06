-- KulaCRM Multi-Tenant SaaS Migration Script
-- Date: 2026-08-01

SET FOREIGN_KEY_CHECKS = 0;

-- 1. Create SaaS Subscription Plans Table
CREATE TABLE IF NOT EXISTS `subscription_plans` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `code` VARCHAR(50) NOT NULL UNIQUE,
  `price_monthly` DOUBLE NOT NULL DEFAULT 0,
  `price_yearly` DOUBLE NOT NULL DEFAULT 0,
  `max_users` INT(11) NOT NULL DEFAULT 5,
  `max_livestock` INT(11) NOT NULL DEFAULT 100,
  `max_sheds` INT(11) NOT NULL DEFAULT 5,
  `features_json` LONGTEXT DEFAULT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seed Default Subscription Plans
INSERT INTO `subscription_plans` (`id`, `name`, `code`, `price_monthly`, `price_yearly`, `max_users`, `max_livestock`, `max_sheds`, `features_json`, `is_active`) VALUES
(1, 'Free Tier', 'free', 0, 0, 2, 50, 2, '{"reports": true, "api_access": false}', 1),
(2, 'Starter Farm', 'starter', 29, 290, 5, 500, 5, '{"reports": true, "api_access": true}', 1),
(3, 'Professional Farm', 'pro', 79, 790, 20, 2500, 20, '{"reports": true, "api_access": true, "multi_shed": true}', 1),
(4, 'Enterprise / Commercial', 'enterprise', 199, 1990, 9999, 99999, 999, '{"reports": true, "api_access": true, "dedicated_support": true}', 1)
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`), `price_monthly`=VALUES(`price_monthly`), `price_yearly`=VALUES(`price_yearly`);

-- 2. Create SaaS Tenants (Organizations) Table
CREATE TABLE IF NOT EXISTS `tenants` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(100) NOT NULL UNIQUE,
  `custom_domain` VARCHAR(255) DEFAULT NULL,
  `owner_id` INT(11) DEFAULT NULL,
  `status` VARCHAR(50) NOT NULL DEFAULT 'active',
  `plan_id` INT(11) NOT NULL DEFAULT 1,
  `trial_ends_at` DATETIME DEFAULT NULL,
  `logo_url` VARCHAR(255) DEFAULT NULL,
  `phone` VARCHAR(50) DEFAULT NULL,
  `email` VARCHAR(255) DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `plan_id` (`plan_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seed Default Tenant (ID 1)
INSERT INTO `tenants` (`id`, `name`, `slug`, `status`, `plan_id`, `email`) VALUES
(1, 'Kula Demo Farm', 'default', 'active', 4, 'admin@kulacrm.com')
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`);

-- 3. Create SaaS Subscriptions Table
CREATE TABLE IF NOT EXISTS `subscriptions` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `tenant_id` INT(11) NOT NULL,
  `plan_id` INT(11) NOT NULL,
  `status` VARCHAR(50) NOT NULL DEFAULT 'active',
  `current_period_start` DATETIME NOT NULL,
  `current_period_end` DATETIME NOT NULL,
  `gateway` VARCHAR(50) DEFAULT 'stripe',
  `gateway_subscription_id` VARCHAR(255) DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `tenant_id` (`tenant_id`),
  KEY `plan_id` (`plan_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seed Default Subscription
INSERT INTO `subscriptions` (`id`, `tenant_id`, `plan_id`, `status`, `current_period_start`, `current_period_end`, `gateway`) VALUES
(1, 1, 4, 'active', '2026-01-01 00:00:00', '2030-01-01 00:00:00', 'system')
ON DUPLICATE KEY UPDATE `status`=VALUES(`status`);

-- 4. Helper Procedure / Direct Injections of tenant_id into domain tables
-- We add tenant_id to domain tables if column doesn't exist
DROP PROCEDURE IF EXISTS AddTenantIdColumn;
DELIMITER $$
CREATE PROCEDURE AddTenantIdColumn(IN tableName VARCHAR(64))
BEGIN
  IF NOT EXISTS (
    SELECT * FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = tableName AND COLUMN_NAME = 'tenant_id'
  ) THEN
    SET @sql = CONCAT('ALTER TABLE `', tableName, '` ADD COLUMN `tenant_id` INT(11) NOT NULL DEFAULT 1, ADD INDEX (`tenant_id`)');
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
  END IF;
END$$
DELIMITER ;

-- Execute stored procedure for all domain tables
CALL AddTenantIdColumn('client');
CALL AddTenantIdColumn('client_payment');
CALL AddTenantIdColumn('client_type');
CALL AddTenantIdColumn('expense');
CALL AddTenantIdColumn('expense_category');
CALL AddTenantIdColumn('expense_payment');
CALL AddTenantIdColumn('expense_subcategory');
CALL AddTenantIdColumn('food_distributed_summary');
CALL AddTenantIdColumn('food_distributed_value');
CALL AddTenantIdColumn('food_purchase_summary');
CALL AddTenantIdColumn('food_purchase_value');
CALL AddTenantIdColumn('food_summary');
CALL AddTenantIdColumn('food_value');
CALL AddTenantIdColumn('food_wasted');
CALL AddTenantIdColumn('live_assigned_shed');
CALL AddTenantIdColumn('live_assigned_shed_summary');
CALL AddTenantIdColumn('livestock');
CALL AddTenantIdColumn('livestock_death_quantity');
CALL AddTenantIdColumn('livestock_purchase_summary');
CALL AddTenantIdColumn('livestock_purchase_value');
CALL AddTenantIdColumn('livestock_reproduction');
CALL AddTenantIdColumn('livestock_sale_summary');
CALL AddTenantIdColumn('livestock_sale_value');
CALL AddTenantIdColumn('livestock_transfer_quantity');
CALL AddTenantIdColumn('livestock_type');
CALL AddTenantIdColumn('medicine');
CALL AddTenantIdColumn('product');
CALL AddTenantIdColumn('product_assign');
CALL AddTenantIdColumn('product_category');
CALL AddTenantIdColumn('product_sale_summary');
CALL AddTenantIdColumn('product_sale_value');
CALL AddTenantIdColumn('product_stock');
CALL AddTenantIdColumn('product_wasted');
CALL AddTenantIdColumn('purchase');
CALL AddTenantIdColumn('sale');
CALL AddTenantIdColumn('settings');
CALL AddTenantIdColumn('shed');
CALL AddTenantIdColumn('staff');
CALL AddTenantIdColumn('staff_payment');
CALL AddTenantIdColumn('staff_type');
CALL AddTenantIdColumn('supplier');
CALL AddTenantIdColumn('supplier_payment');
CALL AddTenantIdColumn('supplier_type');
CALL AddTenantIdColumn('unit');
CALL AddTenantIdColumn('users');
CALL AddTenantIdColumn('vaccination');
CALL AddTenantIdColumn('vaccine');
CALL AddTenantIdColumn('vaccine_dose_assigned_quantity');
CALL AddTenantIdColumn('vaccine_dose_status');
CALL AddTenantIdColumn('vaccine_purchase_summary');
CALL AddTenantIdColumn('vaccine_purchase_value');
CALL AddTenantIdColumn('vaccine_route');
CALL AddTenantIdColumn('vaccine_used');
CALL AddTenantIdColumn('vaccine_wasted');

DROP PROCEDURE IF EXISTS AddTenantIdColumn;

SET FOREIGN_KEY_CHECKS = 1;

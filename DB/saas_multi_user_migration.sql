-- KulaCRM Enterprise Multi-User & Role Management System Migration Script
-- Date: 2026-08-06

SET FOREIGN_KEY_CHECKS = 0;

-- 1. Create Departments Table
CREATE TABLE IF NOT EXISTS `departments` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `tenant_id` INT(11) NOT NULL DEFAULT 1,
  `name` VARCHAR(100) NOT NULL,
  `code` VARCHAR(20) DEFAULT NULL,
  `description` TEXT DEFAULT NULL,
  `head_user_id` INT(11) DEFAULT NULL,
  `status` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_tenant_dept` (`tenant_id`, `status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Create Job Titles Table
CREATE TABLE IF NOT EXISTS `job_titles` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `tenant_id` INT(11) NOT NULL DEFAULT 1,
  `department_id` INT(11) DEFAULT NULL,
  `title` VARCHAR(100) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_tenant_job` (`tenant_id`, `department_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Create / Update Roles Table
CREATE TABLE IF NOT EXISTS `roles` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `tenant_id` INT(11) DEFAULT NULL, -- NULL for global system roles
  `name` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(100) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `is_system` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_tenant_role` (`tenant_id`, `is_system`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seed Standard System Roles (Global, is_system = 1)
INSERT INTO `roles` (`id`, `tenant_id`, `name`, `slug`, `description`, `is_system`) VALUES
(1, NULL, 'Owner', 'owner', 'Full administrative and business control over the organization', 1),
(2, NULL, 'Farm Manager', 'manager', 'Operational command over farm operations, staff, and reporting', 1),
(3, NULL, 'Veterinary Doctor', 'veterinary_doctor', 'Livestock health, medical treatments, vaccinations, and mortality management', 1),
(4, NULL, 'Sales Officer', 'sales_officer', 'Customer relations, product/livestock sales, and client invoicing', 1),
(5, NULL, 'Cashier', 'cashier', 'Point of sale, payment collection, and client receipts', 1),
(6, NULL, 'Store Keeper', 'store_keeper', 'Inventory control, feed, medicine, and warehouse stock', 1),
(7, NULL, 'Accountant', 'accountant', 'Financial auditing, expense tracking, supplier payments, and payroll', 1),
(8, NULL, 'Supervisor', 'supervisor', 'Shed management, task oversight, animal transfers, and worker supervision', 1),
(9, NULL, 'Security', 'security', 'Gate control, animal dispatch validation, and site security logs', 1),
(10, NULL, 'Worker', 'worker', 'Daily farm tasks, feeding execution, and basic observational entries', 1),
(11, NULL, 'Data Entry Officer', 'data_entry_officer', 'Livestock registration, feeding counts, and stock entry', 1)
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`), `description`=VALUES(`description`), `is_system`=VALUES(`is_system`);

-- 4. Create Permissions Table
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `category` VARCHAR(50) NOT NULL,
  `name` VARCHAR(100) NOT NULL UNIQUE,
  `description` VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_category` (`category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seed Master Permission Definitions
INSERT INTO `permissions` (`id`, `category`, `name`, `description`) VALUES
-- Livestock Permissions
(1, 'livestock', 'livestock.view', 'View livestock catalog and details'),
(2, 'livestock', 'livestock.create', 'Register new livestock entries'),
(3, 'livestock', 'livestock.update', 'Edit existing livestock records'),
(4, 'livestock', 'livestock.delete', 'Delete or soft-delete livestock entries'),
(5, 'livestock', 'livestock.export', 'Export livestock records'),
(6, 'livestock', 'livestock.approve', 'Approve livestock reproduction or transfers'),
-- Shed Permissions
(7, 'shed', 'shed.view', 'View shed structures and allocations'),
(8, 'shed', 'shed.create', 'Create new sheds'),
(9, 'shed', 'shed.update', 'Edit shed details and parameters'),
(10, 'shed', 'shed.delete', 'Delete sheds'),
(11, 'shed', 'shed.transfer', 'Execute livestock shed transfers'),
(12, 'shed', 'shed.death', 'Record livestock deaths in sheds'),
-- Vaccine & Health Permissions
(13, 'vaccine', 'vaccine.view', 'View vaccines and vaccination schedules'),
(14, 'vaccine', 'vaccine.create', 'Add new vaccine definitions'),
(15, 'vaccine', 'vaccine.update', 'Update vaccine records'),
(16, 'vaccine', 'vaccine.delete', 'Delete vaccine entries'),
(17, 'vaccine', 'vaccine.purchase', 'Record vaccine purchases'),
(18, 'vaccine', 'vaccine.schedule', 'Manage vaccination schedules and routing'),
-- Food & Feed Permissions
(19, 'food', 'food.view', 'View feed inventory and distributions'),
(20, 'food', 'food.create', 'Register new feed items'),
(21, 'food', 'food.update', 'Edit feed inventory data'),
(22, 'food', 'food.delete', 'Delete feed records'),
(23, 'food', 'food.purchase', 'Record feed purchases'),
(24, 'food', 'food.distribute', 'Distribute feed to sheds'),
-- Medicine Permissions
(25, 'medicine', 'medicine.view', 'View medicine stock'),
(26, 'medicine', 'medicine.create', 'Add new medicine entries'),
(27, 'medicine', 'medicine.update', 'Edit medicine details'),
(28, 'medicine', 'medicine.delete', 'Delete medicine entries'),
-- Sales Permissions
(29, 'sale', 'sale.view', 'View sales invoices and logs'),
(30, 'sale', 'sale.create', 'Create sales invoices'),
(31, 'sale', 'sale.update', 'Edit sales invoices'),
(32, 'sale', 'sale.delete', 'Delete sales records'),
(33, 'sale', 'sale.export', 'Export sales data'),
-- Purchase Permissions
(34, 'purchase', 'purchase.view', 'View purchase logs'),
(35, 'purchase', 'purchase.create', 'Create purchase orders'),
(36, 'purchase', 'purchase.update', 'Edit purchase details'),
(37, 'purchase', 'purchase.delete', 'Delete purchase orders'),
-- Client Permissions
(38, 'client', 'client.view', 'View clients directory'),
(39, 'client', 'client.create', 'Add new clients'),
(40, 'client', 'client.update', 'Edit client profiles'),
(41, 'client', 'client.delete', 'Delete client records'),
(42, 'client', 'client.payment', 'Record client payments'),
-- Supplier Permissions
(43, 'supplier', 'supplier.view', 'View suppliers directory'),
(44, 'supplier', 'supplier.create', 'Add new suppliers'),
(45, 'supplier', 'supplier.update', 'Edit supplier details'),
(46, 'supplier', 'supplier.delete', 'Delete supplier records'),
(47, 'supplier', 'supplier.payment', 'Record payments to suppliers'),
-- Expense & Finance Permissions
(48, 'expense', 'expense.view', 'View expenses and financial records'),
(49, 'expense', 'expense.create', 'Record new expenses'),
(50, 'expense', 'expense.update', 'Edit expense entries'),
(51, 'expense', 'expense.delete', 'Delete expense logs'),
(52, 'expense', 'expense.approve', 'Approve pending expense requests'),
-- Staff & Payroll Permissions
(53, 'staff', 'staff.view', 'View staff directory'),
(54, 'staff', 'staff.create', 'Add new staff members'),
(55, 'staff', 'staff.update', 'Edit staff details'),
(56, 'staff', 'staff.delete', 'Delete staff entries'),
(57, 'staff', 'staff.payroll', 'Manage staff payroll and payments'),
-- Report Permissions
(58, 'reports', 'reports.view', 'View system reports'),
(59, 'reports', 'reports.export', 'Export reports to CSV/PDF'),
-- Settings Permissions
(60, 'settings', 'settings.view', 'View tenant settings'),
(61, 'settings', 'settings.update', 'Update system configuration'),
-- User & Role Management Permissions
(62, 'users', 'users.view', 'View organization user list'),
(63, 'users', 'users.create', 'Create organization users'),
(64, 'users', 'users.update', 'Edit user profiles and roles'),
(65, 'users', 'users.delete', 'Suspend or delete organization users'),
(66, 'users', 'users.invite', 'Invite users via email'),
(67, 'roles', 'roles.view', 'View roles and permission matrices'),
(68, 'roles', 'roles.manage', 'Create and modify tenant roles')
ON DUPLICATE KEY UPDATE `category`=VALUES(`category`), `description`=VALUES(`description`);

-- 5. Create Role Permissions Junction Table
CREATE TABLE IF NOT EXISTS `role_permissions` (
  `role_id` INT(11) NOT NULL,
  `permission_id` INT(11) NOT NULL,
  PRIMARY KEY (`role_id`, `permission_id`),
  KEY `idx_permission_role` (`permission_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Assign ALL permissions to Owner role (role_id = 1)
INSERT IGNORE INTO `role_permissions` (`role_id`, `permission_id`)
SELECT 1, `id` FROM `permissions`;

-- Assign Manager permissions (role_id = 2)
INSERT IGNORE INTO `role_permissions` (`role_id`, `permission_id`)
SELECT 2, `id` FROM `permissions` WHERE `name` NOT IN ('roles.manage', 'settings.update');

-- Assign Vet Doctor permissions (role_id = 3)
INSERT IGNORE INTO `role_permissions` (`role_id`, `permission_id`)
SELECT 3, `id` FROM `permissions` WHERE `category` IN ('livestock', 'shed', 'vaccine', 'medicine') OR `name` IN ('food.view', 'reports.view');

-- Assign Sales Officer permissions (role_id = 4)
INSERT IGNORE INTO `role_permissions` (`role_id`, `permission_id`)
SELECT 4, `id` FROM `permissions` WHERE `category` IN ('sale', 'client') OR `name` IN ('livestock.view', 'reports.view');

-- Assign Cashier permissions (role_id = 5)
INSERT IGNORE INTO `role_permissions` (`role_id`, `permission_id`)
SELECT 5, `id` FROM `permissions` WHERE `name` IN ('sale.view', 'sale.create', 'client.view', 'client.payment', 'reports.view');

-- Assign Store Keeper permissions (role_id = 6)
INSERT IGNORE INTO `role_permissions` (`role_id`, `permission_id`)
SELECT 6, `id` FROM `permissions` WHERE `category` IN ('food', 'medicine') OR `name` IN ('purchase.view', 'reports.view');

-- Assign Accountant permissions (role_id = 7)
INSERT IGNORE INTO `role_permissions` (`role_id`, `permission_id`)
SELECT 7, `id` FROM `permissions` WHERE `category` IN ('expense') OR `name` IN ('sale.view', 'purchase.view', 'client.payment', 'supplier.payment', 'staff.payroll', 'reports.view', 'reports.export');

-- Assign Supervisor permissions (role_id = 8)
INSERT IGNORE INTO `role_permissions` (`role_id`, `permission_id`)
SELECT 8, `id` FROM `permissions` WHERE `name` IN ('livestock.view', 'shed.view', 'shed.transfer', 'shed.death', 'food.view', 'food.distribute');

-- Assign Worker permissions (role_id = 10)
INSERT IGNORE INTO `role_permissions` (`role_id`, `permission_id`)
SELECT 10, `id` FROM `permissions` WHERE `name` IN ('livestock.view', 'shed.view', 'food.view');

-- 6. Create User Roles Junction Table
CREATE TABLE IF NOT EXISTS `user_roles` (
  `user_id` INT(11) NOT NULL,
  `role_id` INT(11) NOT NULL,
  PRIMARY KEY (`user_id`, `role_id`),
  KEY `idx_role_user` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. Create Tenant User Metadata Table
CREATE TABLE IF NOT EXISTS `tenant_users` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `tenant_id` INT(11) NOT NULL DEFAULT 1,
  `user_id` INT(11) NOT NULL,
  `employee_number` VARCHAR(50) DEFAULT NULL,
  `department_id` INT(11) DEFAULT NULL,
  `job_title_id` INT(11) DEFAULT NULL,
  `status` VARCHAR(20) NOT NULL DEFAULT 'active', -- active, inactive, suspended, pending
  `phone` VARCHAR(50) DEFAULT NULL,
  `emergency_contact` VARCHAR(255) DEFAULT NULL,
  `profile_photo` VARCHAR(255) DEFAULT NULL,
  `notes` TEXT DEFAULT NULL,
  `language` VARCHAR(20) DEFAULT 'english',
  `timezone` VARCHAR(50) DEFAULT 'UTC',
  `notification_preferences` LONGTEXT DEFAULT NULL,
  `2fa_enabled` TINYINT(1) NOT NULL DEFAULT 0,
  `2fa_secret` VARCHAR(255) DEFAULT NULL,
  `last_login_at` DATETIME DEFAULT NULL,
  `last_login_ip` VARCHAR(45) DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_tenant_user` (`tenant_id`, `user_id`),
  KEY `idx_tenant_dept` (`tenant_id`, `department_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8. Create User Invitations Table
CREATE TABLE IF NOT EXISTS `user_invitations` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `tenant_id` INT(11) NOT NULL DEFAULT 1,
  `email` VARCHAR(255) NOT NULL,
  `role_id` INT(11) NOT NULL,
  `department_id` INT(11) DEFAULT NULL,
  `token` VARCHAR(100) NOT NULL UNIQUE,
  `invited_by` INT(11) NOT NULL,
  `status` VARCHAR(20) NOT NULL DEFAULT 'pending', -- pending, accepted, expired
  `expires_at` DATETIME NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_tenant_token` (`tenant_id`, `token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 9. Create Login History Table
CREATE TABLE IF NOT EXISTS `login_history` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tenant_id` INT(11) NOT NULL DEFAULT 1,
  `user_id` INT(11) NOT NULL,
  `ip_address` VARCHAR(45) NOT NULL,
  `user_agent` VARCHAR(255) DEFAULT NULL,
  `status` VARCHAR(20) NOT NULL, -- success, failed, locked
  `login_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_tenant_user_login` (`tenant_id`, `user_id`, `login_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 10. Seed Default Departments for Default Tenant (ID 1)
INSERT INTO `departments` (`id`, `tenant_id`, `name`, `code`, `description`, `status`) VALUES
(1, 1, 'Administration', 'ADMIN', 'Executive and management department', 1),
(2, 1, 'Veterinary & Health', 'VET', 'Animal health, vaccinations, and disease control', 1),
(3, 1, 'Finance & Accounting', 'FIN', 'Financial accounting, payroll, and billing', 1),
(4, 1, 'Sales & Marketing', 'SALES', 'Livestock and product sales', 1),
(5, 1, 'Operations & Sheds', 'OPS', 'Farm operations, shed maintenance, and workers', 1),
(6, 1, 'Warehouse & Stock', 'WH', 'Feed, medicine, and product storage', 1),
(7, 1, 'Procurement', 'PROC', 'Suppliers and purchasing management', 1),
(8, 1, 'Security', 'SEC', 'Site security and dispatch verification', 1)
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`);

-- 11. Migration Logic: Auto-promote existing tenant users to Owner role (Role ID = 1)
INSERT IGNORE INTO `tenant_users` (`tenant_id`, `user_id`, `department_id`, `status`)
SELECT COALESCE(`tenant_id`, 1), `id`, 1, 'active' FROM `users`;

INSERT IGNORE INTO `user_roles` (`user_id`, `role_id`)
SELECT `id`, 1 FROM `users`;

SET FOREIGN_KEY_CHECKS = 1;

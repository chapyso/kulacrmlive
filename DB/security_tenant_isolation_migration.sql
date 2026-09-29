-- Tenant isolation & platform-admin hardening (idempotent)

-- 1. payment table had no tenant_id: scope_tenant('payment') silently skipped filtering
ALTER TABLE `payment` ADD COLUMN IF NOT EXISTS `tenant_id` INT(11) NOT NULL DEFAULT 1;
ALTER TABLE `payment` ADD INDEX IF NOT EXISTS `idx_payment_tenant` (`tenant_id`);
UPDATE `payment` p JOIN `sale` s ON p.sale_id = s.id SET p.tenant_id = s.tenant_id WHERE p.sale_id IS NOT NULL AND p.sale_id > 0;
UPDATE `payment` p JOIN `purchase` u ON p.purchase_id = u.id SET p.tenant_id = u.tenant_id WHERE p.purchase_id IS NOT NULL AND p.purchase_id > 0;

-- 2. Explicit platform admin flag (replaces username-based detection)
ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `account_type` VARCHAR(30) NOT NULL DEFAULT 'tenant_user';
UPDATE `users` SET `account_type` = 'platform_admin' WHERE `email` = 'ronaldi2040@gmail.com';
-- To promote another platform admin manually:
--   UPDATE users SET account_type='platform_admin' WHERE id = <id>;

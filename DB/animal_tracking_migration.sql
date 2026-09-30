-- Individual animal tracking (idempotent). Also created automatically by Animal_model on first use.
CREATE TABLE IF NOT EXISTS `livestock_animal` (
  `an_id` int(11) NOT NULL AUTO_INCREMENT,
  `tenant_id` int(11) NOT NULL DEFAULT 1,
  `an_purs_id` int(11) NOT NULL DEFAULT 0,
  `an_purv_id` int(11) NOT NULL DEFAULT 0,
  `an_ls_id` int(11) NOT NULL DEFAULT 0,
  `an_lst_id` int(11) NOT NULL DEFAULT 0,
  `an_name` varchar(100) NOT NULL DEFAULT '',
  `an_status` int(11) NOT NULL DEFAULT 1,
  `an_created_by` int(11) NOT NULL DEFAULT 0,
  `an_created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `an_updated_by` int(11) NOT NULL DEFAULT 0,
  `an_updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`an_id`),
  KEY `idx_an_tenant_purv` (`tenant_id`, `an_purv_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `product_stock` ADD COLUMN IF NOT EXISTS `prs_animal_id` int(11) NOT NULL DEFAULT 0;

CREATE TABLE IF NOT EXISTS `saas_currencies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(10) NOT NULL,
  `name` varchar(100) NOT NULL,
  `symbol` varchar(20) NOT NULL,
  `exchange_rate` decimal(15,4) NOT NULL DEFAULT '1.0000',
  `symbol_position` enum('prefix','suffix') NOT NULL DEFAULT 'prefix',
  `decimal_digits` int(11) NOT NULL DEFAULT '2',
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `saas_currencies` (`code`, `name`, `symbol`, `exchange_rate`, `symbol_position`, `decimal_digits`, `is_default`, `is_active`) VALUES
('UGX', 'Ugandan Shilling', 'UGX', 3750.0000, 'suffix', 0, 1, 1),
('USD', 'US Dollar', '$', 1.0000, 'prefix', 2, 0, 1),
('EUR', 'Euro', '€', 0.9200, 'prefix', 2, 0, 1),
('GBP', 'British Pound', '£', 0.7900, 'prefix', 2, 0, 1),
('KES', 'Kenyan Shilling', 'KSh', 129.0000, 'prefix', 2, 0, 1),
('TZS', 'Tanzanian Shilling', 'TSh', 2650.0000, 'prefix', 0, 0, 1),
('NGN', 'Nigerian Naira', '₦', 1550.0000, 'prefix', 2, 0, 1),
('ZAR', 'South African Rand', 'R', 18.5000, 'prefix', 2, 0, 1),
('GHS', 'Ghanaian Cedi', 'GH₵', 15.2000, 'prefix', 2, 0, 1),
('INR', 'Indian Rupee', '₹', 83.5000, 'prefix', 2, 0, 1),
('CAD', 'Canadian Dollar', 'CA$', 1.3600, 'prefix', 2, 0, 1),
('AUD', 'Australian Dollar', 'A$', 1.5100, 'prefix', 2, 0, 1)
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`), `symbol` = VALUES(`symbol`);

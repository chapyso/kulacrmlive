-- KulaAI Vision Livestock Identification & Smart Counting Database Migration

CREATE TABLE IF NOT EXISTS `ai_vision_counting_sessions` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `tenant_id` INT(11) NOT NULL,
  `session_code` VARCHAR(50) NOT NULL,
  `user_id` INT(11) NOT NULL,
  `farm_id` INT(11) DEFAULT NULL,
  `shed_id` INT(11) NOT NULL,
  `batch_id` INT(11) DEFAULT NULL,
  `start_time` DATETIME NOT NULL,
  `end_time` DATETIME DEFAULT NULL,
  `status` VARCHAR(30) NOT NULL DEFAULT 'in_progress',
  `expected_count` INT(11) NOT NULL DEFAULT 0,
  `confirmed_count` INT(11) NOT NULL DEFAULT 0,
  `unknown_count` INT(11) NOT NULL DEFAULT 0,
  `needs_review_count` INT(11) NOT NULL DEFAULT 0,
  `difference_count` INT(11) NOT NULL DEFAULT 0,
  `notes` TEXT DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `tenant_id` (`tenant_id`),
  KEY `shed_id` (`shed_id`),
  KEY `batch_id` (`batch_id`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `ai_vision_session_records` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `tenant_id` INT(11) NOT NULL,
  `session_id` INT(11) NOT NULL,
  `livestock_id` INT(11) DEFAULT NULL,
  `tag_number` VARCHAR(100) DEFAULT NULL,
  `variant_id` INT(11) DEFAULT NULL,
  `identification_method` VARCHAR(50) DEFAULT 'ear_tag',
  `identification_status` VARCHAR(50) DEFAULT 'confirmed',
  `confidence` DECIMAL(5,2) DEFAULT 0.00,
  `candidate_matches_json` LONGTEXT DEFAULT NULL,
  `visual_features_json` LONGTEXT DEFAULT NULL,
  `first_detected_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `last_detected_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `is_counted` TINYINT(1) DEFAULT 1,
  `review_status` VARCHAR(50) DEFAULT 'approved',
  `snapshot_path` VARCHAR(255) DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `tenant_id` (`tenant_id`),
  KEY `session_id` (`session_id`),
  KEY `livestock_id` (`livestock_id`),
  KEY `tag_number` (`tag_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


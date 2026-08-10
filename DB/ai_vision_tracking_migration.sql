-- KulaAI Vision Persistent Animal Tracking Migration

ALTER TABLE `ai_vision_session_records`
ADD COLUMN IF NOT EXISTS `tracking_id` VARCHAR(30) DEFAULT NULL AFTER `livestock_id`,
ADD COLUMN IF NOT EXISTS `tracking_color` VARCHAR(30) DEFAULT NULL AFTER `tracking_id`,
ADD COLUMN IF NOT EXISTS `reacquisition_count` INT(11) DEFAULT 0 AFTER `tracking_color`,
ADD COLUMN IF NOT EXISTS `tracking_duration_sec` INT(11) DEFAULT 0 AFTER `reacquisition_count`;

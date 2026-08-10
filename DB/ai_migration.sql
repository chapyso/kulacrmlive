-- KulaAI Intelligence Layer Database Migration
-- Adds minimal audit trail table for AI interactions and actions.

CREATE TABLE IF NOT EXISTS `ai_audit_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tenant_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `prompt_summary` text DEFAULT NULL,
  `tools_used` text DEFAULT NULL,
  `action_type` varchar(100) DEFAULT 'query',
  `execution_status` varchar(50) DEFAULT 'success',
  `approval_status` varchar(50) DEFAULT 'not_applicable',
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `tenant_id` (`tenant_id`),
  KEY `user_id` (`user_id`),
  KEY `created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

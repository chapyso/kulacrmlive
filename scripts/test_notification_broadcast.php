<?php
define('BASEPATH', true);
require 'application/config/database.php';
$m = new mysqli($db['default']['hostname'], $db['default']['username'], $db['default']['password'], $db['default']['database']);

echo "=== TESTING SUPER ADMIN NOTIFICATION BROADCAST LOGIC ===\n";

// 1. Insert test broadcast notification
$stmt = $m->prepare("INSERT INTO notifications (tenant_id, user_id, type, title, message, icon, icon_bg, icon_color, link, is_read, channel, priority, sent_by, created_at) VALUES (1, NULL, 'announcement', 'System Scheduled Maintenance', 'KulaCRM SaaS platform will undergo scheduled maintenance tonight at 02:00 UTC.', 'fa-triangle-exclamation', '#fffbeb', '#d97706', 'http://localhost:8000', 0, 'both', 'warning', 'SaaS Super Admin', NOW())");

if ($stmt->execute()) {
    $inserted_id = $stmt->insert_id;
    echo "✅ Broadcast notification created successfully! (ID: {$inserted_id})\n";
} else {
    echo "❌ Failed to insert notification: " . $stmt->error . "\n";
}

// 2. Query notifications history
$res = $m->query("SELECT id, tenant_id, type, title, message, channel, priority, sent_by, created_at FROM notifications ORDER BY id DESC LIMIT 5");
echo "\n--- Recent Dispatched Notifications ---\n";
while ($row = $res->fetch_assoc()) {
    echo "#{$row['id']} | Tenant:{$row['tenant_id']} | Priority:{$row['priority']} | Channel:{$row['channel']} | Title: {$row['title']}\n";
}

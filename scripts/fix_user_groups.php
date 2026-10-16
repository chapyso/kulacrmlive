<?php
$m = new mysqli('127.0.0.1', 'root', '', 'livestock');

// Check if user 102 already in users_groups
$check = $m->query("SELECT * FROM users_groups WHERE user_id = 102 AND group_id = 1");
if ($check->num_rows == 0) {
    $m->query("INSERT INTO users_groups (user_id, group_id) VALUES (102, 1)");
    echo "[SUCCESS] Assigned user 102 (kula@gmail.com) to 'admin' group (ID 1).\n";
} else {
    echo "[INFO] User 102 already in 'admin' group.\n";
}

// Check all tenant users and ensure they have group mappings
$users = $m->query("SELECT id, email, account_type FROM users");
while($u = $users->fetch_assoc()) {
    $has_group = $m->query("SELECT COUNT(*) as c FROM users_groups WHERE user_id = " . $u['id'])->fetch_assoc()['c'];
    if ($has_group == 0) {
        // assign admin or member
        $target_group = ($u['account_type'] === 'platform_admin') ? 7 : 1;
        $m->query("INSERT INTO users_groups (user_id, group_id) VALUES ({$u['id']}, {$target_group})");
        echo "[FIXED] Assigned user {$u['id']} ({$u['email']}) to group {$target_group}.\n";
    }
}

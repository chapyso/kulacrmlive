<?php
$m = new mysqli('127.0.0.1', 'root', '', 'livestock');
$res = $m->query("SELECT ug.*, u.email, g.name as group_name FROM users_groups ug JOIN users u ON ug.user_id = u.id JOIN `groups` g ON ug.group_id = g.id");
while($row = $res->fetch_assoc()) {
    echo "User {$row['user_id']} ({$row['email']}) -> Group {$row['group_id']} ({$row['group_name']})\n";
}

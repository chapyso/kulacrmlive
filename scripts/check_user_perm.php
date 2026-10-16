<?php
$m = new mysqli('127.0.0.1', 'root', '', 'livestock');
$u = $m->query("SELECT * FROM users WHERE email='kula@gmail.com'")->fetch_assoc();
echo "USER:\n";
print_r($u);

if ($u) {
    $ug = $m->query("SELECT ug.*, g.name as group_name FROM users_groups ug JOIN `groups` g ON ug.group_id=g.id WHERE ug.user_id=" . $u['id'])->fetch_all(MYSQLI_ASSOC);
    echo "USER GROUPS:\n";
    print_r($ug);
}

$g = $m->query("SELECT * FROM `groups`")->fetch_all(MYSQLI_ASSOC);
echo "ALL GROUPS:\n";
print_r($g);

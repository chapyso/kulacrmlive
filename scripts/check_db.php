<?php
define('BASEPATH', true);
define('DATABASENAME', 'online');
require '/var/www/html/application/config/database.php';

$host = $db['default']['hostname'];
$user = $db['default']['username'];
$pass = $db['default']['password'];
$name = $db['default']['database'];

echo "EVALUATED CONFIG:" . PHP_EOL;
echo "Host: " . $host . PHP_EOL;
echo "User: " . $user . PHP_EOL;
echo "Pass: " . (empty($pass) ? "EMPTY" : "SET") . PHP_EOL;
echo "Name: " . $name . PHP_EOL;

$m = @new mysqli($host, $user, $pass, $name);
if ($m->connect_error) {
    echo "MYSQLI_ERROR: " . $m->connect_error . PHP_EOL;
} else {
    echo "MYSQLI_SUCCESSFULLY_CONNECTED!" . PHP_EOL;
}

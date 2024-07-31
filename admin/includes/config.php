<?php 
// DB credentials.
define('DB_HOST','localhost');

// ONLINE
// define('DB_USER','u510162695_tms');
// define('DB_PASS','1Tms_password');
// define('DB_NAME','u510162695_tms');

// OFFLINE
define('DB_USER','root');
define('DB_PASS','');
define('DB_NAME','tms');

// Establish database connection.
try
{
$dbh = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME,DB_USER, DB_PASS,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
}
catch (PDOException $e)
{
exit("Error: " . $e->getMessage());
}
?>

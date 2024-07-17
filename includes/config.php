<?php 
// DB credentials.
define('DB_HOST','localhost');
define('DB_USER','u510162695_tms');
define('DB_PASS','1Tms_password');
define('DB_NAME','u510162695_tms');
// Establish database connection.
try
{
$dbh = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME,DB_USER, DB_PASS);
}
catch (PDOException $e)
{
exit("Error: " . $e->getMessage());
}
?>

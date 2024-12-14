<?php
session_start();
include('includes/config.php');

// Clear session_id in the database
if (isset($_SESSION['alogin'])) {
    $uname = $_SESSION['alogin'];
    $sql = "UPDATE admin SET session_id = NULL WHERE UserName = :uname";
    $query = $dbh->prepare($sql);
    $query->bindParam(':uname', $uname, PDO::PARAM_STR);
    $query->execute();
}

// Destroy the session
$_SESSION = array();
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 60 * 60, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
}
session_destroy();
header("location:index.php");
exit();
?>

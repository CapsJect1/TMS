<?php
session_start();
include('includes/config.php');

// Check if user is logged in
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];

    // Mark all sessions for this user as inactive
    $sql = "UPDATE tblsessions SET is_active = 0 WHERE user_id = :user_id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $query->execute();
}

// Clear the session data
$_SESSION = array();
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 60 * 60,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
unset($_SESSION['login']);
session_destroy();

// Redirect to index page
header("Location: index.php");
exit();
?>

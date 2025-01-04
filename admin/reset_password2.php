<?php
session_start();
include('includes/config.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require "../phpmailer/src/Exception.php";
require "../phpmailer/src/PHPMailer.php";
require "../phpmailer/src/SMTP.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    $token = $_GET['token'];

    // Validate token and check its expiration
    $query = $dbh->prepare("SELECT reset_token, token_expiration FROM admin WHERE reset_token = :token");
    $query->bindParam(':token', $token, PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $token_expiration = new DateTime($result['token_expiration']);
        $now = new DateTime();

        // Check if the token has expired
        if ($now > $token_expiration) {
            echo "The reset link has expired.";
            exit();
        }

        // Check if passwords match
        if ($new_password === $confirm_password) {
            // Hash the new password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            // Update the password in the database
            $update = $dbh->prepare("UPDATE admin SET Password = :password, reset_token = NULL, token_expiration = NULL WHERE reset_token = :token");
            $update->bindParam(':password', $hashed_password, PDO::PARAM_STR);
            $update->bindParam(':token', $token, PDO::PARAM_STR);
            $update->execute();

            echo "Password has been successfully reset. You can now <a href='login.php'>login</a> with your new password.";
            exit();
        } else {
            echo "Passwords do not match.";
            exit();
        }
    } else {
        echo "Invalid or expired reset token.";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body, html {
            height: 100%;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f8f9fa;
        }
        .card {
            width: 100%;
            max-width: 400px;
            border: none;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .btn-primary {
            background-color: #3AAF08 !important;
            border: none !important;
        }
    </style>
</head>
<body>
    <div class="card p-4">
        <a href="forgot-password.php" class="bg">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#38AF05" class="bi bi-arrow-left" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
            </svg>
        </a>
        <h2 class="text-center mb-4">Reset Password</h2>
        <form method="POST" action="">
            <div class="mb-3">
                <label for="new_password" class="form-label">New Password</label>
                <input 
                    type="password" 
                    id="new_password" 
                    name="new_password" 
                    class="form-control" 
                    placeholder="Enter your new password" 
                    required>
            </div>
            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirm Password</label>
                <input 
                    type="password" 
                    id="confirm_password" 
                    name="confirm_password" 
                    class="form-control" 
                    placeholder="Confirm your new password" 
                    required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Reset Password</button>
        </form>
    </div>
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

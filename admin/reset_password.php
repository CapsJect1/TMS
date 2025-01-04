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
    $email = $_SESSION['email'];
    $otp = $_POST['otp'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if the OTP is valid
    $query = $dbh->prepare("SELECT otp, otp_expiration FROM tblusers WHERE EmailId = :email");
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $otp_expiration = new DateTime($result['otp_expiration']);
        $now = new DateTime();

        // Check if the OTP has expired
        if ($now > $otp_expiration) {
            echo "<script>swal('Error', 'The OTP has expired.', 'error');</script>";
            exit();
        }

        // Check if OTP matches
        if ($otp == $result['otp']) {
            // Check if passwords match
            if ($new_password === $confirm_password) {
                // Hash the new password
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

                // Update password in database
                $update = $dbh->prepare("UPDATE tblusers SET Password = :password, otp = NULL, otp_expiration = NULL WHERE EmailId = :email");
                $update->bindParam(':password', $hashed_password, PDO::PARAM_STR);
                $update->bindParam(':email', $email, PDO::PARAM_STR);
                $update->execute();

                // Show SweetAlert success notification
                echo "<script>swal('Success', 'Your password has been successfully reset.', 'success').then(function() { window.location = 'index.php'; });</script>";
                exit();
            } else {
                echo "<script>swal('Error', 'Passwords do not match.', 'error');</script>";
                exit();
            }
        } else {
            echo "<script>swal('Error', 'Invalid OTP.', 'error');</script>";
            exit();
        }
    } else {
        echo "<script>swal('Error', 'Email not found or OTP expired.', 'error');</script>";
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        <h2 class="text-center mb-4">Reset Password</h2>
        <form method="POST" action="">
            <div class="mb-3">
                <label for="otp" class="form-label">Enter OTP</label>
                <input 
                    type="text" 
                    id="otp" 
                    name="otp" 
                    class="form-control" 
                    placeholder="Enter your OTP" 
                    required>
            </div>
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

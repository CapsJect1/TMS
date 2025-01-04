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

    // Validate OTP
    $query = $dbh->prepare("SELECT otp, otp_expiration FROM admin WHERE EmailId = :email");
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $stored_otp = $result['otp'];
        $otp_expiration = new DateTime($result['otp_expiration']);
        $now = new DateTime();

        // Check if OTP matches and is not expired
        if ($otp === $stored_otp && $now <= $otp_expiration) {
            // Check if passwords match
            if ($new_password === $confirm_password) {
                // Hash the new password
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

                // Update password in the database
                $update = $dbh->prepare("UPDATE admin SET Password = :password, otp = NULL, otp_expiration = NULL WHERE EmailId = :email");
                $update->bindParam(':password', $hashed_password, PDO::PARAM_STR);
                $update->bindParam(':email', $email, PDO::PARAM_STR);
                $update->execute();

                // Display success message
                echo "<script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Password Reset Successful',
                            text: 'Your password has been successfully reset. You can now login with your new password.',
                            confirmButtonText: 'Okay'
                        }).then(function() {
                            window.location.href = 'login.php';
                        });
                      </script>";
                exit();
            } else {
                echo "<script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Passwords do not match',
                            text: 'Please make sure both passwords are the same.',
                            confirmButtonText: 'Try Again'
                        });
                      </script>";
            }
        } else {
            echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid or Expired OTP',
                        text: 'The OTP is either invalid or has expired. Please request a new one.',
                        confirmButtonText: 'Okay'
                    });
                  </script>";
        }
    } else {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No account found with this email.',
                    confirmButtonText: 'Okay'
                });
              </script>";
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
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.3.4/dist/sweetalert2.min.css" rel="stylesheet">
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
                    placeholder="Enter OTP" 
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
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.3.4/dist/sweetalert2.min.js"></script>
</body>
</html>

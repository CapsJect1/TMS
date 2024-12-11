<?php
session_start();
include ('includes/config.php'); // Include your database connection file

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require "./phpmailer/src/Exception.php";
require "./phpmailer/src/PHPMailer.php";
require "./phpmailer/src/SMTP.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    // Check if the email exists in the database
    $stmt = $conn->prepare("SELECT * FROM users WHERE EmailId = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $otp = rand(100000, 999999); // Generate a 6-digit OTP
        $expiration_time = date("Y-m-d H:i:s", strtotime('+15 minutes'));

        // Insert OTP into otp_verification table
        $stmt = $conn->prepare("INSERT INTO otp_verification (email, otp, expiration_time) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE otp = ?, expiration_time = ?");
        $stmt->bind_param("sssss", $email, $otp, $expiration_time, $otp, $expiration_time);
        $stmt->execute();

        // Send OTP to email using PHPMailer
        $mail = new PHPMailer(true);
        try {
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'percebuhayan12@gmail.com';
            $mail->Password = 'jnolufsoqvqbsjim';
            $mail->Port = 587;

            $mail->setFrom('santafe@gmail.com', 'TMS Santa Fe');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Password Reset OTP';
            $mail->Body = "<p>Your OTP for password reset is: <strong>$otp</strong></p><p>This OTP is valid for 15 minutes.</p>";

            $mail->send();

            // Store email in session and redirect to reset_password.php
            $_SESSION['email'] = $email;
            echo "<script>alert('OTP sent to your email.'); window.location.href = 'reset_password.php';</script>";
        } catch (Exception $e) {
            echo "<script>alert('Failed to send OTP. Please try again.');</script>";
        }
    } else {
        echo "<script>alert('Email not found. Please check your email address.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h3 class="text-center mb-4">Forgot Password</h3>
                        <form action="forgot_password1.php" method="POST">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Enter your registered email" required>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Send OTP</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
session_start();
include('includes/config.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require "phpmailer/src/Exception.php";
require "phpmailer/src/PHPMailer.php";
require "phpmailer/src/SMTP.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['EmailId'];

    // Check if email exists in the database
    $query = $dbh->prepare("SELECT id FROM tblusers WHERE EmailId = :email");
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->execute();

    if ($query->rowCount() > 0) {
        // Generate reset token and its expiration
        $reset_token = bin2hex(random_bytes(32)); // Generate a random token
        $token_expiration = date("Y-m-d H:i:s", strtotime('+1 hour')); // Token expiration in 1 hour

        // Update database with reset token and expiration
        $update = $dbh->prepare("UPDATE tblusers SET reset_token = :reset_token, token_expiration = :token_expiration WHERE EmailId = :email");
        $update->bindParam(':reset_token', $reset_token, PDO::PARAM_STR);
        $update->bindParam(':token_expiration', $token_expiration, PDO::PARAM_STR);
        $update->bindParam(':email', $email, PDO::PARAM_STR);
        $update->execute();

        // Send password reset link via email
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'percebuhayan12@gmail.com'; // Your Gmail email address
            $mail->Password = 'jnolufsoqvqbsjim'; // Your Gmail app password
            $mail->Port = 587;
            $mail->setFrom('santafe@gmail.com', 'TMS Santa Fe');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request';
            $reset_link = "https://santafeport.com/reset_password2.php?token=$reset_token";
            $mail->Body = "Click on the following link to reset your password: <a href='$reset_link'>$reset_link</a><br>This link will expire in 1 hour.";

            $mail->send();

            $_SESSION['email'] = $email;
            header("Location: reset_password2.php");
            exit();
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "Email not found in our records.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
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
        <a  href="forgot-password.php" class="bg">
			<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#38AF05" class="bi bi-arrow-left" viewBox="0 0 16 16">
				<path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
			</svg>
		</a>
        <h2 class="text-center mb-4">Forgot Password</h2>
        <form method="POST" action="">
            <div class="mb-3">
                <label for="EmailId" class="form-label">Enter your Email ID</label>
                <input 
                    type="email" 
                    id="EmailId" 
                    name="EmailId" 
                    class="form-control" 
                    placeholder="Enter your email" 
                    required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Send Reset Link</button>
        </form>
        <div class="text-center mt-3">
            <small class="text-muted">We will send a reset link to your registered email.</small>
        </div>
    </div>
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

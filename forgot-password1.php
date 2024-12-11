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
        // Generate OTP and its expiration
        $otp = rand(100000, 999999);
        $otp_expiration = date("Y-m-d H:i:s", strtotime('+15 minutes'));

        // Update database with OTP and expiration
        $update = $dbh->prepare("UPDATE tblusers SET otp = :otp, otp_expiration = :otp_expiration WHERE EmailId = :email");
        $update->bindParam(':otp', $otp, PDO::PARAM_STR);
        $update->bindParam(':otp_expiration', $otp_expiration, PDO::PARAM_STR);
        $update->bindParam(':email', $email, PDO::PARAM_STR);
        $update->execute();

        // Send OTP email
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'percebuhayan12@gmail.com';
            $mail->Password = 'jnolufsoqvqbsjim';
            $mail->Port = 587;
            $mail->setFrom('santafe@gmail.com', 'TMS Santa Fe');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Your OTP for Password Reset';
            $mail->Body = "Your OTP for password reset is: <b>$otp</b><br>This OTP is valid for 15 minutes.";
            $mail->send();

            $_SESSION['email'] = $email;
            header("Location: reset_password.php");
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
            <button type="submit" class="btn btn-primary w-100">Send OTP</button>
        </form>
        <div class="text-center mt-3">
            <small class="text-muted">We will send an OTP to your registered email.</small>
        </div>
    </div>
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>



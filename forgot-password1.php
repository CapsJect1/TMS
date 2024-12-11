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
<html>
<head>
   
</head>
<body>
    <h2>Forgot Password</h2>
    <form method="POST" action="">
        <label for="EmailId">Enter your Email ID:</label>
        <input type="email" id="EmailId" name="EmailId" required>
        <button type="submit">Send OTP</button>
    </form>
</body>
</html>

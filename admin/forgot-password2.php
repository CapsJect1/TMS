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

    // Check if email exists in the admin table
    $query = $dbh->prepare("SELECT id FROM admin WHERE EmailId = :email");
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->execute();

    if ($query->rowCount() > 0) {
        // Generate reset token and its expiration
        $reset_token = bin2hex(random_bytes(32)); // Generate a random token
        $token_expiration = date("Y-m-d H:i:s", strtotime('+1 hour')); // Token expiration in 1 hour

        // Update database with reset token and expiration
        $update = $dbh->prepare("UPDATE admin SET reset_token = :reset_token, token_expiration = :token_expiration WHERE EmailId = :email");
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
            $reset_link = "https://santafeport.com/admin/reset_password2.php?token=$reset_token";
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

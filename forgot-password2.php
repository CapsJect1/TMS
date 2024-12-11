<?php
session_start();
include('includes/config.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require "phpmailer/src/Exception.php";
require "phpmailer/src/PHPMailer.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['EmailId'];

    // Check if email exists in the database
    $query = $dbh->prepare("SELECT id FROM tblusers WHERE EmailId = :email");
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->execute();

    if ($query->rowCount() > 0) {
        // Generate secure token and expiration
        $token = bin2hex(random_bytes(32));  // Generate a secure token
        $token_expiration = date("Y-m-d H:i:s", strtotime('+1 hour')); // Token expiration in 1 hour

        // Update database with token and expiration
        $update = $dbh->prepare("UPDATE tblusers SET reset_token = :token, token_expiration = :expiration WHERE EmailId = :email");
        $update->bindParam(':token', $token, PDO::PARAM_STR);
        $update->bindParam(':expiration', $token_expiration, PDO::PARAM_STR);
        $update->bindParam(':email', $email, PDO::PARAM_STR);
        $update->execute();

        // Generate reset link
        $resetLink = "https://santafeport.com/reset_password.php?token=" . $token;

        // Send email with reset link
        $mail = new PHPMailer(true);
        try {
            // Configure PHPMailer with your settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Set mail server
            $mail->SMTPAuth = true;
            $mail->Username = 'percebuhayan12@gmail.com'; // Your email address
            $mail->Password = 'jnolufsoqvqbsjim'; // Your email password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Recipients
            $mail->setFrom('santafe@gmail.com', 'TMS Santa Fe');
            $mail->addAddress($email);

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request';
            $mail->Body = "Click the link below to reset your password:<br><br>
                          <a href='$resetLink'>$resetLink</a><br><br>
                          This link is valid for 1 hour.";

            $mail->send();

            echo "Password reset link has been sent to your email.";
        } catch (Exception $e) {
            echo "Mailer Error: {$mail->ErrorInfo}";
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="card p-4 mx-auto" style="max-width: 400px;">
            <h2 class="text-center">Forgot Password</h2>
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="EmailId" class="form-label">Enter your Email</label>
                    <input type="email" id="EmailId" name="EmailId" class="form-control" placeholder="Enter your email" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Send Reset Link</button>
            </form>
        </div>
    </div>
</body>
</html>

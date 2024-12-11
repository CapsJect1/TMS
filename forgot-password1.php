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
</head>
    <style>
        .cards {
            height: 150vh;
        }
        .btn-primary {
            background-color: #3AAF08 !important; 
            border: none !important;
        }
    </style>
<body>
    </br>
    </br>
    </br>
</br></br></br>
    </br>
    
    <div class="cards container mt-5">
       <div class="cards">
            <div class=" justify-content-center d-flex">
            <div class="shadow p-4">
                 <form method="POST" action="" style="max-width:500px; width:100%;";>
                     <h2 class="text-center">
                         FORGOT PASSWORD
                     </h2>
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
            </div>
                       
       </div>  
       </div>
    </div>
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


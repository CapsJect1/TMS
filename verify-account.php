<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
</head>
<body>
<?php
session_start();
include ('includes/config.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

error_reporting(E_ALL);

require "./phpmailer/src/Exception.php";
require "./phpmailer/src/PHPMailer.php";
require "./phpmailer/src/SMTP.php";

    if (isset($_GET['verification'])) {
        $email = clean($_GET['email']);
        $verification = clean($_GET['verification']);

        $stmt = $dbh->prepare("SELECT * FROM tblusers WHERE EmailId = :email AND Verification = :verification");
        $stmt->execute([':email' => $email, ':verification' => $verification]);
        
        if ($stmt->rowCount() > 0) {
            $status = 2;
            $userpin = password_hash(rand(uniqid()), PASSWORD_DEFAULT);
            $update = $dbh->prepare("UPDATE tblusers SET Status = :status, UserPin = :userpin WHERE EmailId = :email AND Verification = :verification");
            $update->execute([':status' => $status, ':userpin' => $userpin, ':email' => $email, ':verification' => $verification]);
           

            $mail = new PHPMailer(true);
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'percebuhayan12@gmail.com';
            $mail->Password = 'jnolufsoqvqbsjim';
            $mail->Port = 587;

            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );

            $mail->setFrom('santafe@gmail.com', 'TMS Santa Fe');
            $mail->addAddress($email);
            $mail->Subject = "User Pin | Forgot Password";
            $mail->Body = "Hello,\n You are successfully registered\n If you forgot your password you can change it using your Pin below\n PIN: $userpin \n Sincerely,\n Santa Fe, Cebu City,Philippines - Port";

            $mail->send();

            if ($update) {
                echo "<script>
                Swal.fire({
                    title: 'Success!',
                    text: 'Account confirmed successfully, You can login now',
                    icon: 'success',
                    timer: 3000,
                    showConfirmButton: false
                }).then(function(){
                    window.location.href = 'index.php'
                });
            </script>";
            }

        }else{
            echo "<script>
            Swal.fire({
                title: 'Error!',
                text: 'Incorrect Verification or Email',
                icon: 'error',
                timer: 1500,
                showConfirmButton: false
            });
        </script>";
        }
    }
    ?>

<script>
// document.addEventListener("DOMContentLoaded", function(){
//   const newUrl = '/';

// // Change the URL without refreshing the page
// history.pushState(null, '', newUrl);
// })
</script>
</body>

</html>
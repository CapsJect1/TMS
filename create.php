<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require './phpmailer/src/Exception.php';
require './phpmailer/src/PHPMailer.php';
require './phpmailer/src/SMTP.php';

session_start();
error_reporting(E_ALL);

// Database connection
try {
    $dbh = new PDO('mysql:host=localhost;dbname=your_database_name', 'your_username', 'your_password');
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_register'])) {
    $verification = uniqid() . rand(100, 999999999);
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $mnumber = $_POST['mobilenumber'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate name
    if (preg_match('/\d/', $fname)) {
        $error = "First name should not contain numbers.";
    } elseif (preg_match('/\d/', $lname)) {
        $error = "Last name should not contain numbers.";
    }
    // Validate password strength
    elseif (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password)) {
        $error = "Password must be at least 8 characters long, include one uppercase letter, one lowercase letter, one number, and one special character.";
    } else {
        // Prepare user data for database
        $full = $fname . ' ' . $lname;
        $password = password_hash($password, PASSWORD_DEFAULT);

        try {
            // Insert into database
            $sql = "INSERT INTO tblusers (FullName, fname, lname, MobileNumber, EmailId, Password, Verification) 
                    VALUES (:full, :fname, :lname, :mnumber, :email, :password, :verification)";
            $query = $dbh->prepare($sql);
            $query->bindParam(':full', $full, PDO::PARAM_STR);
            $query->bindParam(':fname', $fname, PDO::PARAM_STR);
            $query->bindParam(':lname', $lname, PDO::PARAM_STR);
            $query->bindParam(':mnumber', $mnumber, PDO::PARAM_STR);
            $query->bindParam(':email', $email, PDO::PARAM_STR);
            $query->bindParam(':password', $password, PDO::PARAM_STR);
            $query->bindParam(':verification', $verification, PDO::PARAM_STR);
            $query->execute();

            $lastInsertId = $dbh->lastInsertId();
            if ($lastInsertId) {
                // Send verification email
                $mail = new PHPMailer(true);
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'your_email@gmail.com';
                $mail->Password = 'your_email_password';
                $mail->Port = 587;
                $mail->SMTPOptions = [
                    'ssl' => [
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    ]
                ];
                $mail->setFrom('your_email@gmail.com', 'Your App Name');
                $mail->addAddress($email);
                $mail->Subject = "Email Account Verification";
                $mail->Body = "Click this link to verify your account: https://yourdomain.com/verify-account.php?verification=" . $verification . "&email=" . $email;
                $mail->send();

                $success = true; // Trigger SweetAlert
            }
        } catch (Exception $e) {
            $error = "Error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.all.min.js"></script>
</head>
<body>
    <h2>User Registration</h2>
    <?php if (isset($error)): ?>
        <div style="color: red;"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="create.php">
        <label for="fname">First Name:</label>
        <input type="text" id="fname" name="fname" required oninput="validateName()">
        <br><br>

        <label for="lname">Last Name:</label>
        <input type="text" id="lname" name="lname" required oninput="validateName()">
        <br><br>

        <label for="mobilenumber">Mobile Number:</label>
        <input type="text" id="mobilenumber" name="mobilenumber" required>
        <br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required oninput="validatePassword()">
        <small>Password must include at least 8 characters, one uppercase, one lowercase, one number, and one special character.</small>
        <br><br>

        <button type="submit" name="submit_register">Register</button>
    </form>

    <?php if (isset($success)): ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Registration Successful!',
            text: 'A verification email has been sent. Please check your email.',
            confirmButtonText: 'OK'
        }).then(() => {
            window.location.href = 'thankyou.php';
        });
    </script>
    <?php endif; ?>
</body>
</html>

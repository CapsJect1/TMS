<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require "./phpmailer/src/Exception.php";
require "./phpmailer/src/PHPMailer.php";
require "./phpmailer/src/SMTP.php";
require "includes/config.php"; // Include your database configuration

if (isset($_POST['submit_register'])) {
    $verification = uniqid() . rand(100, 999999999);
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $mnumber = $_POST['mobilenumber'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Server-side validation for names
    if (preg_match('/\d/', $fname) || preg_match('/\d/', $lname)) {
        echo "<script>
                Swal.fire('Error', 'Names should not contain numbers.', 'error');
              </script>";
        exit;
    }

    // Server-side validation for strong password
    if (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password)) {
        echo "<script>
                Swal.fire('Error', 'Invalid password format.', 'error');
              </script>";
        exit;
    }

    $full = $fname . ' ' . $lname;
    $password = password_hash($password, PASSWORD_DEFAULT);

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
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'your-email@gmail.com';
            $mail->Password = 'your-email-password';
            $mail->Port = 587;

            $mail->setFrom('no-reply@example.com', 'Your App Name');
            $mail->addAddress($email);
            $mail->Subject = "Verify Your Email";
            $mail->Body = "Click this link to verify: https://example.com/verify.php?code=$verification";

            $mail->send();

            echo "<script>
                    Swal.fire('Success', 'You are registered. Please verify your email.', 'success')
                        .then(() => { window.location.href = 'login.php'; });
                  </script>";
        } catch (Exception $e) {
            echo "<script>
                    Swal.fire('Error', 'Unable to send verification email.', 'error');
                  </script>";
        }
    } else {
        echo "<script>
                Swal.fire('Error', 'Something went wrong. Please try again.', 'error');
              </script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <!-- SweetAlert CSS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        form {
            width: 400px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
        }
        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }
        .error {
            font-size: 12px;
            color: red;
        }
        .success {
            font-size: 12px;
            color: green;
        }
    </style>
</head>
<body>
    <h1 style="text-align:center;">User Registration</h1>
    <form method="POST" action="create.php">
        <input type="text" name="fname" id="fname" placeholder="First Name" required oninput="validateName()">
        <span id="fname-error" class="error"></span>

        <input type="text" name="lname" id="lname" placeholder="Last Name" required oninput="validateName()">
        <span id="lname-error" class="error"></span>

        <input type="text" name="mobilenumber" placeholder="Mobile Number" required>
        <input type="email" name="email" placeholder="Email" required>

        <input type="password" name="password" id="password" placeholder="Password" required oninput="validatePassword()">
        <span id="password-criteria" class="error"></span>

        <button type="submit" name="submit_register" id="submit" disabled>Register</button>
    </form>

    <script>
        function validatePassword() {
            const password = document.getElementById('password').value;
            const submitButton = document.getElementById('submit');
            const passwordCriteria = document.getElementById('password-criteria');
            const strongPasswordRegex = /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

            if (!strongPasswordRegex.test(password)) {
                passwordCriteria.textContent = "Password must include uppercase, lowercase, number, special character, and be at least 8 characters.";
                submitButton.disabled = true;
            } else {
                passwordCriteria.textContent = "Password is strong.";
                passwordCriteria.className = "success";
                submitButton.disabled = false;
            }
        }

        function validateName() {
            const fname = document.getElementById('fname').value;
            const lname = document.getElementById('lname').value;
            const fnameError = document.getElementById('fname-error');
            const lnameError = document.getElementById('lname-error');
            const submitButton = document.getElementById('submit');
            let valid = true;

            if (/\d/.test(fname)) {
                fnameError.textContent = 'First name should not contain numbers.';
                valid = false;
            } else {
                fnameError.textContent = '';
            }

            if (/\d/.test(lname)) {
                lnameError.textContent = 'Last name should not contain numbers.';
                valid = false;
            } else {
                lnameError.textContent = '';
            }

            submitButton.disabled = !valid;
        }
    </script>
</body>
</html>

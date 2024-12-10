<?php
session_start();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if there was an error with login attempts
if (isset($_SESSION['ERROR_LOGIN'])) {
    if ($_SESSION['date'] == date('Y-m-d')) {
        unset($_SESSION['ERROR_LOGIN']);
    }
}

// Process the sign-in form submission
if (isset($_POST['signin'])) {
    echo "Form submitted!<br>"; // Debugging message to check form submission
    
    // Check if the user is locked out
    if (isset($_SESSION['ERROR_LOGIN']) && $_SESSION['ERROR_LOGIN']['count'] >= 3) {
        echo "<script>
        Swal.fire({
            title: 'Error!',
            text: 'Login trial expired, please try again later',
            icon: 'error',
            showConfirmButton: true,
            timer: 60000, // 1 minute timer
            timerProgressBar: true
        });
        </script>";
        echo "<script>window.location.href = 'index.php';</script>";            
        exit;
    }

    // Google reCAPTCHA verification
    $recaptcha_secret = '6LezNpMqAAAAAKA-tks15YZHfdpFeWhQZo2kj-gb'; // Secret key
    $recaptcha_response = $_POST['g-recaptcha-response'];

    // Make request to verify reCAPTCHA response
    $recaptcha_verify_url = "https://www.google.com/recaptcha/api/siteverify";
    $recaptcha_verify_response = file_get_contents($recaptcha_verify_url . "?secret=" . $recaptcha_secret . "&response=" . $recaptcha_response);
    $recaptcha_result = json_decode($recaptcha_verify_response);
    
    if (!$recaptcha_result->success) {
        echo "reCAPTCHA verification failed!<br>"; // Debugging message
        echo "<script>
            Swal.fire({
                title: 'Error!',
                text: 'Please complete the reCAPTCHA verification.',
                icon: 'error',
                showConfirmButton: true,
                timer: 60000, // 1 minute timer
                timerProgressBar: true
            });
            </script>";
        echo "<script>window.location.href = 'index.php';</script>";
        exit;
    }

    // Continue with your login logic
    $email = htmlspecialchars(stripslashes(trim($_POST['email'])));
    $password = htmlspecialchars(stripslashes(trim($_POST['password'])));
    $status = 2; // Only active users with status 2 can log in

    // Debugging message for email and password
    echo "Email: $email<br>Password: $password<br>";

    // SQL query to fetch user details based on email and password
    try {
        $sql = "SELECT id, FullName, EmailId, fname, lname, Password, Status, login_attempts, lock_time FROM tblusers WHERE EmailId=:email AND Status = :stat";
        $query = $dbh->prepare($sql);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->bindParam(':stat', $status, PDO::PARAM_INT);
        $query->execute();
        $user = $query->fetch(PDO::FETCH_ASSOC);
        
        // Debugging message to check the user fetched from the database
        echo "User data: " . print_r($user, true) . "<br>";
    } catch (Exception $e) {
        echo "Database query failed: " . $e->getMessage();
        exit;
    }

    if ($query->rowCount() > 0) {
        if ($user['Status'] == 2) {
            // Check if the account is locked
            if ($user['lock_time'] && strtotime($user['lock_time']) > time()) {
                $time_left = strtotime($user['lock_time']) - time();
                echo "<script>
                    Swal.fire({
                        title: 'Error!',
                        text: 'Account locked. Try again in " . ceil($time_left / 60) . " minutes.',
                        icon: 'error',
                        showConfirmButton: true,
                        timer: 60000, // 1 minute timer
                        timerProgressBar: true
                    });
                    </script>";
                echo "<script>window.location.href = 'index.php';</script>";
                exit;
            }

            // Check password
            if (password_verify($password, $user['Password'])) {
                // Reset login attempts after successful login
                try {
                    $sql_update = "UPDATE tblusers SET login_attempts = 0, lock_time = NULL WHERE EmailId = :email";
                    $update_query = $dbh->prepare($sql_update);
                    $update_query->bindParam(':email', $email, PDO::PARAM_STR);
                    $update_query->execute();

                    // Set session variables upon successful login
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['FullName'];
                    $_SESSION['login'] = $user['EmailId'];
                    $_SESSION['fname'] = $user['fname'];
                    $_SESSION['lname'] = $user['lname'];

                    // Redirect to a dashboard or home page after successful login
                    echo "<script>window.location.href = 'package-list.php';</script>";
                } catch (Exception $e) {
                    echo "Error resetting login attempts: " . $e->getMessage();
                    exit;
                }
            } else {
                // Increment login attempts on failure
                try {
                    $sql_update = "UPDATE tblusers SET login_attempts = login_attempts + 1 WHERE EmailId = :email";
                    $update_query = $dbh->prepare($sql_update);
                    $update_query->bindParam(':email', $email, PDO::PARAM_STR);
                    $update_query->execute();

                    // Lock account if attempts exceed 3
                    if ($user['login_attempts'] + 1 >= 3) {
                        $lock_time = date('Y-m-d H:i:s', strtotime('+5 minutes')); // Lock for 5 minutes
                        $sql_lock = "UPDATE tblusers SET lock_time = :lock_time WHERE EmailId = :email";
                        $lock_query = $dbh->prepare($sql_lock);
                        $lock_query->bindParam(':lock_time', $lock_time, PDO::PARAM_STR);
                        $lock_query->bindParam(':email', $email, PDO::PARAM_STR);
                        $lock_query->execute();

                        echo "<script>
                            Swal.fire({
                                title: 'Error!',
                                text: 'Too many failed attempts. Please try again in 5 minutes.',
                                icon: 'error',
                                showConfirmButton: true,
                                timer: 60000, // 1 minute timer
                                timerProgressBar: true
                            });
                            </script>";
                    } else {
                        echo "<script>
                            Swal.fire({
                                title: 'Error!',
                                text: 'Incorrect email or password',
                                icon: 'error',
                                showConfirmButton: true,
                                timer: 60000, // 1 minute timer
                                timerProgressBar: true
                            });
                            </script>";
                    }
                } catch (Exception $e) {
                    echo "Error updating login attempts: " . $e->getMessage();
                    exit;
                }
                echo "<script>window.location.href = 'index.php';</script>";
            }
        } else {
            echo "<script>
            Swal.fire({
                title: 'Error!',
                text: 'Please confirm your account first',
                icon: 'error',
                showConfirmButton: true,
                timer: 60000, // 1 minute timer
                timerProgressBar: true
            });
            </script>";
            echo "<script>window.location.href = 'index.php';</script>";
        }
    } else {
        echo "<script>
            Swal.fire({
                title: 'Error!',
                text: 'Email not registered',
                icon: 'error',
                showConfirmButton: true,
                timer: 60000, // 1 minute timer
                timerProgressBar: true
            });
            </script>";
        echo "<script>window.location.href = 'index.php';</script>";
    }
}
?><!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TMS | Tourism Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>

<!-- Sign-in Form -->
<div class="container mt-5">
    <h3>Sign in with your account</h3>
    <form method="post" name="login">
        <div class="mb-3">
            <input type="email" name="email" class="form-control" placeholder="Enter your Email" required>
        </div>
        <div class="mb-3" style="position: relative;">
            <input type="password" name="password" class="form-control" placeholder="Password" required>
            <i class="fa fa-eye" id="show-pass" style="position: absolute; top: 0; right: 10px; margin: 10px;"></i>
        </div>
        <div class="mb-3">
            <a href="forgot-password.php">Forgot password</a>
        </div>

        <!-- Google reCAPTCHA -->
        <div class="g-recaptcha" data-sitekey="6LezNpMqAAAAAJo_vbJQ6Lo10T2GxhtxeROWoB8p"></div>

        <button type="submit" name="signin" class="btn btn-primary mt-3">SIGN IN</button>
    </form>
</div>

<script>
    document.getElementById('show-pass').onclick = function () {
        var passwordField = document.querySelector('input[name="password"]');
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            this.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            passwordField.type = 'password';
            this.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


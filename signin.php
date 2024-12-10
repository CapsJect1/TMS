<?php
session_start();

if (isset($_SESSION['ERROR_LOGIN'])) {
    if ($_SESSION['date'] == date('Y-m-d')) {
        unset($_SESSION['ERROR_LOGIN']);
    }
}

if (isset($_POST['signin'])) {
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
    }

    // Google reCAPTCHA verification
    $recaptcha_secret = '6LezNpMqAAAAAKA-tks15YZHfdpFeWhQZo2kj-gb'; // Secret key
    $recaptcha_response = $_POST['g-recaptcha-response'];

    // Make request to verify reCAPTCHA response
    $recaptcha_verify_url = "https://www.google.com/recaptcha/api/siteverify";
    $recaptcha_verify_response = file_get_contents($recaptcha_verify_url . "?secret=" . $recaptcha_secret . "&response=" . $recaptcha_response);
    $recaptcha_result = json_decode($recaptcha_verify_response);

    // If reCAPTCHA verification failed, show an error
    if (!$recaptcha_result->success) {
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
    }

    // Continue with your existing login logic
    $email = htmlspecialchars(stripslashes(trim($_POST['email'])));
    $password = htmlspecialchars(stripslashes(trim($_POST['password'])));
    $status = 2;

    // SQL query to fetch user details based on email and password
    $sql = "SELECT id, FullName, EmailId, fname, lname, Password, Status, login_attempts, lock_time FROM tblusers WHERE EmailId=:email AND Status = :stat";
    $query = $dbh->prepare($sql);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->bindParam(':stat', $status, PDO::PARAM_INT);
    $query->execute();
    $user = $query->fetch(PDO::FETCH_ASSOC);

    if ($query->rowCount() > 0) {
        if ($user['Status'] == 2) {
            // Check if account is locked
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
            } else {
                // Increment login attempts on failure
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
?>

<!DOCTYPE HTML>
<html lang="en">

<head>
    <title>TMS | Tourism Management System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <script type="application/javascript">
        addEventListener("load", function () { setTimeout(hideURLbar, 0); }, false);

        function hideURLbar() {
            window.scrollTo(0, 1);
        }

        // Disable right-click context menu
        document.addEventListener('contextmenu', function (e) {
            e.preventDefault();  // Prevent the default context menu
        });
    </script>

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
    
    <!-- SweetAlert CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Google reCAPTCHA CDN -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <!-- Custom CSS -->
    <link href="css/style.css" rel='stylesheet' type='text/css' />
    <link href="css/font-awesome.css" rel="stylesheet">
</head>

<body>

<!-- Static Login Form -->
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card mt-5">
                <div class="card-header">
                    <h5 class="card-title text-center">Sign In</h5>
                </div>
                <div class="card-body">
                    <form method="post" name="login">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="email" class="form-control" placeholder="Enter your Email" required>
                        </div>
                        <div class="mb-3" style="position: relative;">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
                            <i class="fa fa-eye" id="show-pass2" style="position: absolute; top: 0; right: 0; margin: 35px 10px 0 0;"></i>
                        </div>
                        <div class="g-recaptcha mb-3" data-sitekey="6LezNpMqAAAAAJo_vbJQ6Lo10T2GxhtxeROWoB8p"></div>
                        <button type="submit" name="signin" class="btn btn-primary w-100">Sign In</button>
                        <p class="mt-3">Forgot your password? <a href="forgot-password.php">Click here</a></p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let showPass2 = document.getElementById('show-pass2');
    showPass2.onclick = () => {
        let passwordInp = document.forms['login']['password'];
        if (passwordInp.getAttribute('type') == 'password') {
            showPass2.classList.replace('fa-eye', 'fa-eye-slash');
            passwordInp.setAttribute('type', 'text');
        } else {
            showPass2.classList.replace('fa-eye-slash', 'fa-eye');
            passwordInp.setAttribute('type', 'password');
        }
    }
</script>

</body>
</html>

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
            allowOutsideClick: false, // Prevent closing by clicking outside
            allowEscapeKey: false,    // Prevent closing by pressing escape key
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
                allowOutsideClick: false, // Prevent closing by clicking outside
                allowEscapeKey: false,    // Prevent closing by pressing escape key
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
                        allowOutsideClick: false, // Prevent closing by clicking outside
                        allowEscapeKey: false,    // Prevent closing by pressing escape key
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
                            allowOutsideClick: false, // Prevent closing by clicking outside
                            allowEscapeKey: false,    // Prevent closing by pressing escape key
                        });
                        </script>";
                } else {
                    echo "<script>
                        Swal.fire({
                            title: 'Error!',
                            text: 'Incorrect email or password',
                            icon: 'error',
                            showConfirmButton: true,
                            allowOutsideClick: false, // Prevent closing by clicking outside
                            allowEscapeKey: false,    // Prevent closing by pressing escape key
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
                allowOutsideClick: false, // Prevent closing by clicking outside
                allowEscapeKey: false,    // Prevent closing by pressing escape key
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
                allowOutsideClick: false, // Prevent closing by clicking outside
                allowEscapeKey: false,    // Prevent closing by pressing escape key
            });
            </script>";
        echo "<script>window.location.href = 'index.php';</script>";
    }
}
?>


<!-- HTML Form for Login -->
<div class="modal fade" id="myModal4" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content modal-info">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body modal-spa">
                <div class="login-grids">
                    <div class="login">
                        <div class="login-right">
                            <form method="post" name="login">
                                <h3>Sign in with your account</h3>
                                <input type="text" name="email" id="email" placeholder="Enter your Email" required="">
                                <div style="position: relative;">
                                    <input type="password" name="password" id="password" placeholder="Password" value=""
                                        required="">
                                    <i class="fa fa-eye" id="show-pass2" style="position: absolute; top: 0; right: 0; margin: 35px 10px 0 0;"></i>
                                </div>
                                <h4><a href="forgot-password.php">Forgot password</a></h4>

                                <!-- Google reCAPTCHA widget -->
                                <div class="g-recaptcha" data-sitekey="6LezNpMqAAAAAJo_vbJQ6Lo10T2GxhtxeROWoB8p"></div>

                                <input type="submit" name="signin" value="SIGN IN">
                            </form>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <p>By logging in you agree to our <a href="page.php?type=terms">Terms and Conditions</a> and <a
                            href="page.php?type=privacy">Privacy Policy</a></p>
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
            showPass2.classList.replace('fa-eye', 'fa-eye-slash')
            passwordInp.setAttribute('type', 'text')
        } else {
            showPass2.classList.replace('fa-eye-slash', 'fa-eye')
            passwordInp.setAttribute('type', 'password')
        }
    }
</script>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<?php
session_start();

// Google reCAPTCHA verification
if (isset($_POST['signin'])) {
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
                timer: 1500,
                showConfirmButton: false
            });
            </script>";
        exit;
    }

    // Get email and password
    $email = htmlspecialchars(stripslashes(trim($_POST['email'])));
    $password = htmlspecialchars(stripslashes(trim($_POST['password'])));
    $status = 2;

    // SQL query to fetch user details based on email and password
    $sql = "SELECT id, FullName, EmailId, fname, lname, Password, Status FROM tblusers WHERE EmailId=:email AND Status = :stat";
    $query = $dbh->prepare($sql);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->bindParam(':stat', $status, PDO::PARAM_INT);
    $query->execute();
    $user = $query->fetch(PDO::FETCH_ASSOC);

    if ($query->rowCount() > 0) {
        if ($user['Status'] == 2) {
            // Set session variables upon successful login
            if (password_verify($password, $user['Password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['FullName'];
                $_SESSION['login'] = $user['EmailId'];
                $_SESSION['fname'] = $user['fname'];
                $_SESSION['lname'] = $user['lname'];
                // Redirect to a dashboard or home page after successful login
                echo "<script>window.location.href = 'package-list.php';</script>";
            } else {
                // Incorrect credentials
                echo "<script>
                    Swal.fire({
                        title: 'Error!',
                        text: 'Incorrect email or password.',
                        icon: 'error',
                        timer: 1500,
                        showConfirmButton: false
                    });
                    </script>";
            }
        } else {
            // Account status not active
            echo "<script>
                Swal.fire({
                    title: 'Error!',
                    text: 'Please confirm your account first.',
                    icon: 'error',
                    timer: 1500,
                    showConfirmButton: false
                });
                </script>";
        }
    } else {
        // Account not found
        echo "<script>
            Swal.fire({
                title: 'Error!',
                text: 'No account found with this email.',
                icon: 'error',
                timer: 1500,
                showConfirmButton: false
            });
            </script>";
    }
}
?>

<!-- Modal Login Form -->
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
                                    <input type="password" name="password" id="password" placeholder="Password" value="" required="">
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

    // Function to handle login attempts in localStorage
    function handleLoginAttempts(email) {
        let attemptsKey = `login_attempts_${email}`;
        let resetKey = `reset_date_${email}`;
        let currentDate = new Date().toISOString().split('T')[0]; // Get today's date in 'YYYY-MM-DD'

        // Check if the email already has login attempts in localStorage
        let attempts = localStorage.getItem(attemptsKey) || 3;
        let lastResetDate = localStorage.getItem(resetKey);

        // If it's a new day, reset attempts
        if (lastResetDate !== currentDate) {
            attempts = 3;
            localStorage.setItem(resetKey, currentDate);
        }

        // If attempts are used up, show alert
        if (attempts <= 0) {
            Swal.fire({
                title: 'Error!',
                text: 'You have exceeded the maximum number of attempts for today. Please try again tomorrow.',
                icon: 'error',
                timer: 3000,
                showConfirmButton: true
            });
            return false;
        }

        // Update attempts on failed login
        if (attempts > 0) {
            localStorage.setItem(attemptsKey, attempts - 1);
            Swal.fire({
                title: 'Error!',
                text: `Incorrect email or password. You have ${attempts - 1} attempts left.`,
                icon: 'error',
                timer: 3000,
                showConfirmButton: true
            });
        }
        return true;
    }

    // Handle form submission and validate attempts
    document.forms['login'].onsubmit = function (e) {
        e.preventDefault();
        let email = document.getElementById('email').value;
        let password = document.getElementById('password').value;

        // Call the function to handle attempts
        if (handleLoginAttempts(email)) {
            // If the user passes the attempt validation, submit the form (PHP will handle authentication)
            this.submit();
        }
    };
</script>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>


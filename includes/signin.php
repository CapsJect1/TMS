<?php
session_start();

// Initialize session variable for email-specific login attempts if not set
$email = htmlspecialchars(stripslashes(trim($_POST['email'])));
if (!isset($_SESSION['ERROR_LOGIN'][$email])) {
    $_SESSION['ERROR_LOGIN'][$email] = ['count' => 0, 'date' => date('Y-m-d')];
}

// Reset attempts if a new day
if ($_SESSION['ERROR_LOGIN'][$email]['date'] != date('Y-m-d')) {
    $_SESSION['ERROR_LOGIN'][$email] = ['count' => 0, 'date' => date('Y-m-d')];
}

if (isset($_POST['signin'])) {
    // Check if user has exceeded max attempts
    if ($_SESSION['ERROR_LOGIN'][$email]['count'] >= 3) {
        echo "<script>
        Swal.fire({
            title: 'Error!',
            text: 'You have exceeded the maximum login attempts. Please try again later.',
            icon: 'error',
            showConfirmButton: true
        });
        </script>";
        echo "<script>window.location.href = 'index.php';</script>";
        exit;
    }

    // Google reCAPTCHA verification
    $recaptcha_secret = '6LezNpMqAAAAAKA-tks15YZHfdpFeWhQZo2kj-gb'; // Secret key
    $recaptcha_response = $_POST['g-recaptcha-response'];
    $recaptcha_verify_url = "https://www.google.com/recaptcha/api/siteverify";
    $recaptcha_verify_response = file_get_contents($recaptcha_verify_url . "?secret=" . $recaptcha_secret . "&response=" . $recaptcha_response);
    $recaptcha_result = json_decode($recaptcha_verify_response);

    if (!$recaptcha_result->success) {
        echo "<script>
        Swal.fire({
            title: 'Error!',
            text: 'Please complete the reCAPTCHA verification.',
            icon: 'error',
            showConfirmButton: true
        });
        </script>";
        echo "<script>window.location.href = 'index.php';</script>";
        exit;
    }

    // SQL query to fetch user details based on email and password
    $status = 2;
    $sql = "SELECT id, FullName, EmailId, fname, lname, Password, Status FROM tblusers WHERE EmailId=:email AND Status = :stat";
    $query = $dbh->prepare($sql);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->bindParam(':stat', $status, PDO::PARAM_INT);
    $query->execute();
    $user = $query->fetch(PDO::FETCH_ASSOC);

    if ($query->rowCount() > 0) {
        if ($user['Status'] == 2) {
            // Validate password
            if (password_verify($password, $user['Password'])) {
                // Reset login attempts on successful login
                $_SESSION['ERROR_LOGIN'][$email]['count'] = 0;

                // Set session variables and redirect to the dashboard
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['FullName'];
                $_SESSION['login'] = $user['EmailId'];
                $_SESSION['fname'] = $user['fname'];
                $_SESSION['lname'] = $user['lname'];

                echo "<script>window.location.href = 'package-list.php';</script>";
            } else {
                // Increment login attempts on incorrect password
                $_SESSION['ERROR_LOGIN'][$email]['count'] += 1;

                echo "<script>
                Swal.fire({
                    title: 'Error!',
                    text: 'Incorrect email or password',
                    icon: 'error',
                    showConfirmButton: true
                });
                </script>";
                echo "<script>window.location.href = 'index.php';</script>";
            }
        } else {
            echo "<script>
            Swal.fire({
                title: 'Error!',
                text: 'Please confirm your account first',
                icon: 'error',
                showConfirmButton: true
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
            showConfirmButton: true
        });
        </script>";
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
                            <form method="post" name="login" onsubmit="return checkLoginAttempts()">
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
    // Show/hide password functionality
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

    // Function to check login attempts in localStorage
    function checkLoginAttempts() {
        let emailInput = document.getElementById('email').value;
        let loginAttempts = localStorage.getItem(emailInput) || 0;

        if (loginAttempts >= 3) {
            // Prevent form submission if login attempts exceed 3
            Swal.fire({
                title: 'Error!',
                text: 'You have exceeded the maximum login attempts. Please try again later.',
                icon: 'error',
                showConfirmButton: true
            });
            return false; // Prevent form submission
        }

        // Increment the attempt count for the specific email in localStorage
        loginAttempts++;
        localStorage.setItem(emailInput, loginAttempts);
        return true; // Allow form submission
    }
</script>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<?php
session_start();

// Google reCAPTCHA verification
$recaptcha_secret = '6LezNpMqAAAAAKA-tks15YZHfdpFeWhQZo2kj-gb'; // Secret key
$recaptcha_response = $_POST['g-recaptcha-response'];

if (isset($_POST['signin'])) {
    // Google reCAPTCHA verification
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

                                <input type="submit" name="signin" value="SIGN IN" onclick="checkLoginAttempts(); return false;">
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
    // Show and hide password
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

    // Check login attempts using localStorage
    function checkLoginAttempts() {
        const email = document.getElementById("email").value;
        let attemptsData = JSON.parse(localStorage.getItem(email));

        if (!attemptsData) {
            // If no attempts recorded, initialize the data
            attemptsData = { attempts: 0, lastAttempt: new Date().toISOString() };
            localStorage.setItem(email, JSON.stringify(attemptsData));
        }

        const today = new Date().toISOString().split('T')[0]; // Get current date in YYYY-MM-DD format
        const lastAttemptDate = new Date(attemptsData.lastAttempt).toISOString().split('T')[0];

        // Reset attempts if it's a new day
        if (today !== lastAttemptDate) {
            attemptsData.attempts = 0;
            attemptsData.lastAttempt = new Date().toISOString();
            localStorage.setItem(email, JSON.stringify(attemptsData));
        }

        // Check if attempts exceeded
        if (attemptsData.attempts >= 3) {
            Swal.fire({
                title: 'Error!',
                text: 'Login trial expired, please try again tomorrow.',
                icon: 'error',
                showConfirmButton: true
            });
            return;
        }

        // Simulate password check (replace this with actual password verification logic)
        const password = document.getElementById("password").value;
        const correctPassword = "your_correct_password"; // Replace with actual password check

        if (password === correctPassword) {
            // Successful login
            window.location.href = 'package-list.php';
        } else {
            // Incorrect password, increment attempts
            attemptsData.attempts += 1;
            localStorage.setItem(email, JSON.stringify(attemptsData));

            Swal.fire({
                title: 'Error!',
                text: 'Incorrect email or password',
                icon: 'error',
                showConfirmButton: true
            });
        }
    }
</script>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>

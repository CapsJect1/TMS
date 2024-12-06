<?php 
session_start(); 
if (isset($_POST['signin'])) { 
    $recaptcha_secret = '6LezNpMqAAAAAKA-tks15YZHfdpFeWhQZo2kj-gb'; 
    $recaptcha_response = $_POST['g-recaptcha-response']; 
    $recaptcha_verify_url = "https://www.google.com/recaptcha/api/siteverify"; 
    $recaptcha_verify_response = file_get_contents($recaptcha_verify_url . "?secret=" . $recaptcha_secret . "&response=" . $recaptcha_response); 
    $recaptcha_result = json_decode($recaptcha_verify_response); 
    if (!$recaptcha_result->success) { 
        echo "<script> Swal.fire({title: 'Error!', text: 'Please complete the reCAPTCHA verification.', icon: 'error', timer: 1500, showConfirmButton: false }); </script>"; 
        exit; 
    } 
    $email = htmlspecialchars(stripslashes(trim($_POST['email']))); 
    $password = htmlspecialchars(stripslashes(trim($_POST['password']))); 
    $status = 2; 
    $sql = "SELECT id, FullName, EmailId, fname, lname, Password, Status FROM tblusers WHERE EmailId=:email AND Status = :stat"; 
    $query=$dbh->prepare($sql); 
    $query->bindParam(':email', $email, PDO::PARAM_STR); 
    $query->bindParam(':stat', $status, PDO::PARAM_INT); 
    $query->execute(); 
    $user = $query->fetch(PDO::FETCH_ASSOC); 
    if ($query->rowCount() > 0) { 
        if ($user['Status'] == 2) { 
            if (password_verify($password, $user['Password'])) { 
                $_SESSION['user_id'] = $user['id']; 
                $_SESSION['user_name'] = $user['FullName']; 
                $_SESSION['login'] = $user['EmailId']; 
                $_SESSION['fname'] = $user['fname']; 
                $_SESSION['lname'] = $user['lname']; 
                echo "<script>window.location.href = 'package-list.php';</script>"; 
            } else { 
                echo "<script> Swal.fire({ title: 'Error!', text: 'Incorrect email or password', icon: 'error', timer: 1500, showConfirmButton: false }); </script>"; 
            } 
        } else { 
            echo "<script> Swal.fire({ title: 'Error!', text: 'Please confirm your account first', icon: 'error', timer: 1500, showConfirmButton: false }); </script>"; 
        } 
        exit; 
    } else { 
        echo "<script> Swal.fire({ title: 'Error!', text: 'Invalid email or password', icon: 'error', timer: 1500, showConfirmButton: false }); </script>"; 
    } 
} 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
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

        // Utility function to get current date
        function getCurrentDate() {
            return new Date().toLocaleDateString();
        }

        // Function to check/reset attempts for a specific email
        function checkResetAttempts(email) {
            const lastResetDate = localStorage.getItem(`${email}_last_reset`);
            const currentDate = getCurrentDate();
            if (lastResetDate !== currentDate) {
                localStorage.setItem(`${email}_attempts`, 3); // Reset attempts to 3
                localStorage.setItem(`${email}_last_reset`, currentDate); // Update reset date
            }
        }

        // Function to get remaining attempts for a specific email
        function getRemainingAttempts(email) {
            return localStorage.getItem(`${email}_attempts`) || 3; // Default to 3 attempts if not set
        }

        // Function to decrease attempts after failed login
        function decreaseAttempts(email) {
            let attempts = getRemainingAttempts(email);
            if (attempts > 0) {
                attempts--;
                localStorage.setItem(`${email}_attempts`, attempts);
            }
            return attempts;
        }

        // Function to handle login attempts
        function handleLoginAttempt(email, password) {
            checkResetAttempts(email); // Check if attempts need to be reset

            const remainingAttempts = getRemainingAttempts(email);
            if (remainingAttempts <= 0) {
                alert("You have reached the maximum login attempts for today. Please try again tomorrow.");
                return false; // Prevent login
            }

            // Proceed with your PHP backend authentication here
            const loginSuccessful = false; // Replace with your actual login check logic

            if (loginSuccessful) {
                // Reset attempts on successful login
                localStorage.setItem(`${email}_attempts`, 3);
                return true; // Proceed to the next page or action after login
            } else {
                // Decrease attempts on failed login
                const remainingAfterFailedLogin = decreaseAttempts(email);
                alert(`Incorrect credentials! You have ${remainingAfterFailedLogin} attempts remaining.`);
                return false; // Prevent login
            }
        }

        // Listen for form submit and handle login attempt
        document.querySelector('form[name="login"]').addEventListener('submit', function (e) {
            e.preventDefault(); // Prevent the form from submitting normally

            const email = document.querySelector('input[name="email"]').value;
            const password = document.querySelector('input[name="password"]').value;

            if (handleLoginAttempt(email, password)) {
                // If login was successful, submit the form or redirect
                this.submit(); // Or perform your actual login logic
            }
        });
    </script>
</body>
</html>

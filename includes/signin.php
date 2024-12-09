<?php
session_start();

// Google reCAPTCHA verification
if (isset($_POST['signin'])) {
    $email = htmlspecialchars(stripslashes(trim($_POST['email'])));
    $password = htmlspecialchars(stripslashes(trim($_POST['password'])));

    // SQL query to fetch user details based on email
    $status = 2;
    $sql = "SELECT id, FullName, EmailId, fname, lname, Password, Status FROM tblusers WHERE EmailId=:email AND Status = :stat";
    $query = $dbh->prepare($sql);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->bindParam(':stat', $status, PDO::PARAM_INT);
    $query->execute();
    $user = $query->fetch(PDO::FETCH_ASSOC);

    if ($query->rowCount() > 0) {
        if ($user['Status'] == 2) {
            // If password is correct
            if (password_verify($password, $user['Password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['FullName'];
                $_SESSION['login'] = $user['EmailId'];
                $_SESSION['fname'] = $user['fname'];
                $_SESSION['lname'] = $user['lname'];

                // Redirect after successful login
                echo "<script>window.location.href = 'package-list.php';</script>";
                exit;
            } else {
                // Decrease the attempt count on incorrect password
                echo "<script>
                    Swal.fire({
                        title: 'Error!',
                        text: 'Incorrect email or password',
                        icon: 'error',
                        showConfirmButton: true
                    });
                </script>";
                echo "<script>window.location.href = 'index.php';</script>";
                exit;
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
            exit;
        }
    } else {
        echo "<script>
            Swal.fire({
                title: 'Error!',
                text: 'Email not found',
                icon: 'error',
                showConfirmButton: true
            });
        </script>";
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
// Local storage for tracking login attempts
const maxAttempts = 3;
let attempts = JSON.parse(localStorage.getItem('loginAttempts')) || {};

document.forms['login'].onsubmit = (e) => {
    e.preventDefault(); // Prevent form submission for validation

    const email = document.getElementById('email').value;

    // Check if the email has exceeded the attempts limit
    if (attempts[email] && attempts[email] >= maxAttempts) {
        Swal.fire({
            title: 'Error!',
            text: 'You have exceeded the maximum number of login attempts. Please try again tomorrow.',
            icon: 'error',
            showConfirmButton: true
        });
        return;
    }

    // Perform login validation here
    // Simulate a failed login for demonstration (this should be replaced by actual backend validation)
    let isValidLogin = false; // Set this to true if the login is valid

    if (isValidLogin) {
        // Reset attempts on successful login
        localStorage.removeItem('loginAttempts');
        window.location.href = 'package-list.php'; // Redirect to another page after success
    } else {
        // Increment the attempt count on failure
        attempts[email] = attempts[email] ? attempts[email] + 1 : 1;
        localStorage.setItem('loginAttempts', JSON.stringify(attempts));

        if (attempts[email] >= maxAttempts) {
            Swal.fire({
                title: 'Error!',
                text: 'Incorrect email or password. You have reached the maximum number of attempts.',
                icon: 'error',
                showConfirmButton: true
            });
        } else {
            Swal.fire({
                title: 'Error!',
                text: 'Incorrect email or password',
                icon: 'error',
                showConfirmButton: true
            });
        }
    }
};

// Toggle password visibility
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

<?php
// Verify reCAPTCHA
if (isset($_POST['signin'])) {
    $email = htmlspecialchars(stripslashes(trim($_POST['email'])));
    $password = htmlspecialchars(stripslashes(trim($_POST['password'])));
    $status = 2;

    // reCAPTCHA verification
    $recaptchaSecret = '6LeBZG0qAAAAAHpE8Nr7ZxDcFQw3dVdkeJ4p3stl';  // Replace with your secret key
    $recaptchaResponse = $_POST['g-recaptcha-response'];

    $recaptchaUrl = "https://www.google.com/recaptcha/api/siteverify";
    $response = file_get_contents($recaptchaUrl . '?secret=' . $recaptchaSecret . '&response=' . $recaptchaResponse);
    $responseKeys = json_decode($response, true);

    // If reCAPTCHA is valid
    if (intval($responseKeys["success"]) !== 1) {
        echo "<script>
            Swal.fire({
                title: 'Error!',
                text: 'Please complete the reCAPTCHA to proceed.',
                icon: 'error',
                timer: 1500,
                showConfirmButton: false
            });
            </script>";
        exit;
    }

    // SQL query to fetch user details
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
                // Redirect to dashboard
                echo "<script>window.location.href = 'package-list.php'</script>";
            } else {
                echo "<script>
                    Swal.fire({
                        title: 'Error!',
                        text: 'Incorrect email or password',
                        icon: 'error',
                        timer: 1500,
                        showConfirmButton: false
                    });
                    </script>";
            }
        } else {
            echo "<script>
                Swal.fire({
                    title: 'Error!',
                    text: 'Please confirm your account first',
                    icon: 'error',
                    timer: 1500,
                    showConfirmButton: false
                });
                </script>";
        }
        exit;
    } else {
        echo "<script>
            Swal.fire({
                title: 'Error!',
                text: 'User not found',
                icon: 'error',
                timer: 1500,
                showConfirmButton: false
            });
            </script>";
    }
}
?>

<!-- HTML form for login -->
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
                        <div class="login-left">
                            <!-- Add your logo here -->
                     
                        </div>
                        <div class="login-right">
                            <form method="post" name="login">
                                <h3>Sign in with your account</h3>
                                <input type="text" name="email" id="email" placeholder="Enter your Email" required=""/>
                                <div style="position: relative;">
                                    <input type="password" name="password" id="password" placeholder="Password" value=""
                                        required=""/>
                                    <i class="fa fa-eye" id="show-pass2" style="position: absolute; top: 0; right: 0; margin: 35px 10px 0 0;"></i>
                                </div>
                                <h4><a href="forgot-password.php">Forgot password</a></h4>

                                <!-- Google reCAPTCHA v2 widget -->
                                <div class="g-recaptcha" data-sitekey="6LeBZG0qAAAAAHpE8Nr7ZxDcFQw3dVdkeJ4p3stl"></div>
                                
                                <input type="submit" name="signin" value="SIGN IN">
                            </form>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <p>By logging in you agree to our <a href="https://policies.google.com/terms" target="_blank">Terms of Service</a> and <a
                            href="https://policies.google.com/privacy" target="_blank">Privacy Policy</a> from Google.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>
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

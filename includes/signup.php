
<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require "./phpmailer/src/Exception.php";
require "./phpmailer/src/PHPMailer.php";
require "./phpmailer/src/SMTP.php";

error_reporting(E_ALL);

if (isset($_POST['submit_register'])) {

    $password = $_POST['password'];

    // Server-side password validation
    $errors = [];

    // Check password length
    if (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long.";
    }

    // Check for at least one uppercase letter
    if (!preg_match("/[A-Z]/", $password)) {
        $errors[] = "Password must contain at least one uppercase letter.";
    }

    // Check for at least one lowercase letter
    if (!preg_match("/[a-z]/", $password)) {
        $errors[] = "Password must contain at least one lowercase letter.";
    }

    // Check for at least one number
    if (!preg_match("/[0-9]/", $password)) {
        $errors[] = "Password must contain at least one number.";
    }

    // Check for at least one special character
    if (!preg_match("/[!@#$%^&*(),.?\":{}|<>]/", $password)) {
        $errors[] = "Password must contain at least one special character.";
    }

    // If there are errors, show them and prevent registration
    if (!empty($errors)) {
        $errorMessages = implode("<br>", $errors);
    } else {
        // No errors, proceed with registration
        $verification = uniqid() . rand(100, 999999999);
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $full = $fname . ' ' . $lname;
        $mnumber = $_POST['mobilenumber'];
        $email = $_POST['email'];
        // Hash the password
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // Insert the user into the database
        $sql = "INSERT INTO tblusers(FullName, fname, lname, MobileNumber, EmailId, Password, Verification) 
                VALUES(:full, :fname, :lname, :mnumber, :email, :password, :verification)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':full', $full, PDO::PARAM_STR);
        $query->bindParam(':fname', $fname, PDO::PARAM_STR);
        $query->bindParam(':lname', $lname, PDO::PARAM_STR);
        $query->bindParam(':mnumber', $mnumber, PDO::PARAM_STR);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->bindParam(':password', $passwordHash, PDO::PARAM_STR);
        $query->bindParam(':verification', $verification, PDO::PARAM_STR);
        $query->execute();

        $lastInsertId = $dbh->lastInsertId();

        if ($lastInsertId) {
            // Successful registration, send verification email
            $_SESSION['msg'] = "You are successfully registered. Please verify your account first to login.";

            $mail = new PHPMailer(true);
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'percebuhayan12@gmail.com';
            $mail->Password = 'jnolufsoqvqbsjim';
            $mail->Port = 587;

            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );

            $mail->setFrom('santafe@gmail.com', 'TMS Santa Fe');
            $mail->addAddress($email);
            $mail->Subject = "Email Account Verification";
            $mail->Body = "Click this link to verify account: https://santafeport.com/verify-account.php?verification=" . $verification . "&email=" . $email;

            $mail->send();
            echo "<script>window.location.href = 'thankyou.php';</script>";
        } else {
            $_SESSION['msg'] = "Something went wrong. Please try again.";
            echo "<script>window.location.href = 'thankyou.php';</script>";
        }
    }
}
?>

<!-- HTML Form for Registration -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <section>
                <div class="modal-body modal-spa">
                    <div class="login-grids"> 
                        <div class="login">
                            <div class="login-right">
                                <form method="post" onsubmit="return validatePassword()">
                                    <h3>Create your account</h3>
                                     <div id="password-errors" class="alert alert-danger top" style="display: none; "></div>
                                    <input type="text" placeholder="First Name" name="fname" autocomplete="off" required>
                                    <input type="text" placeholder="Last Name" name="lname" autocomplete="off" required>
                                    <input type="text" placeholder="Mobile number" maxlength="11" name="mobilenumber" autocomplete="off" required>
                                    <input type="text" placeholder="Email id" name="email" id="email" autocomplete="off" required>
                                    
                                    <!-- Show errors dynamically here -->
                                   

                                    <div style="position: relative;">
                                        <input type="password" name="password" id="password" placeholder="Password" value="" minlength="8" required>
                                        <i class="fa fa-eye" id="show-pass" style="position: absolute; top: 0; right: 0; margin: 35px 10px 0 0;"></i>
                                    </div>

                                    <div id="html_element"></div>

                                    <input type="submit" name="submit_register" value="CREATE ACCOUNT">
                                </form>
                            </div>
                        </div>
                        <p>By logging in you agree to our <a href="page.php?type=terms">Terms and Conditions</a> and <a href="page.php?type=privacy">Privacy Policy</a></p>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>

<script>
    // Password validation function (Client-side)
    function validatePassword() {
        var password = document.getElementById('password').value;
        var errorMessages = [];
        var regexUppercase = /[A-Z]/;
        var regexLowercase = /[a-z]/;
        var regexNumbers = /[0-9]/;
        var regexSpecialChars = /[!@#$%^&*(),.?":{}|<>]/;

        if (password.length < 8) {
            errorMessages.push('Password must be at least 8 characters long.');
        }
        if (!regexUppercase.test(password)) {
            errorMessages.push('Password must contain at least one uppercase letter.');
        }
        if (!regexLowercase.test(password)) {
            errorMessages.push('Password must contain at least one lowercase letter.');
        }
        if (!regexNumbers.test(password)) {
            errorMessages.push('Password must contain at least one number.');
        }
        if (!regexSpecialChars.test(password)) {
            errorMessages.push('Password must contain at least one special character.');
        }

        if (errorMessages.length > 0) {
            // Display errors in a Bootstrap alert
            document.getElementById('password-errors').innerHTML = errorMessages.join('<br>');
            document.getElementById('password-errors').style.display = 'block';
            return false; // Prevent form submission
        } else {
            // Hide any previous errors if password is valid
            document.getElementById('password-errors').style.display = 'none';
        }
        return true; // Allow form submission
    }
</script>

<script type="text/javascript">
    var onloadCallback = function() {
        grecaptcha.render('html_element', {
            'sitekey' : '6LeBZG0qAAAAAHpE8Nr7ZxDcFQw3dVdkeJ4p3stl'
        });
    };
</script>

<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>

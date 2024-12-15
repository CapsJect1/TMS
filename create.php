<?php
require "includes/config.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require "./phpmailer/src/Exception.php";
require "./phpmailer/src/PHPMailer.php";
require "./phpmailer/src/SMTP.php";

error_reporting(E_ALL);

if (isset($_POST['submit_register'])) {
    $verification = uniqid() . rand(100, 999999999);
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $mnumber = $_POST['mobilenumber'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Server-side validation for names
    if (preg_match('/\d/', $fname)) {
        die("First name should not contain numbers.");
    }

    if (preg_match('/\d/', $lname)) {
        die("Last name should not contain numbers.");
    }

    // Server-side validation for strong password
    if (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password)) {
        die("Password must be at least 8 characters long and include one uppercase letter, one lowercase letter, one number, and one special character.");
    }

    $full = $fname . ' ' . $lname;
    $password = password_hash($password, PASSWORD_DEFAULT);

    // Insert into the database
    $sql = "INSERT INTO tblusers(FullName,fname,lname,MobileNumber,EmailId,Password,Verification) 
            VALUES(:full, :fname, :lname, :mnumber, :email, :password, :verification)";
    $query = $dbh->prepare($sql);
    $query->bindParam(':full', $full, PDO::PARAM_STR);
    $query->bindParam(':fname', $fname, PDO::PARAM_STR);
    $query->bindParam(':lname', $lname, PDO::PARAM_STR);
    $query->bindParam(':mnumber', $mnumber, PDO::PARAM_STR);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->bindParam(':password', $password, PDO::PARAM_STR);
    $query->bindParam(':verification', $verification, PDO::PARAM_STR);
    $query->execute();

    $lastInsertId = $dbh->lastInsertId();
    if ($lastInsertId) {
        // Send verification email
        $mail = new PHPMailer(true);
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'percebuhayan12@gmail.com';
        $mail->Password = 'jnolufsoqvqbsjim';
        $mail->Port = 587;
        $mail->SMTPOptions = [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            ]
        ];
        $mail->setFrom('santafe@gmail.com', 'TMS Santa Fe');
        $mail->addAddress($email);
        $mail->Subject = "Email Account Verification";
        $mail->Body = "Click this link to verify account: https://santafeport.com/verify-account.php?verification=" . $verification . "&email=" . $email;
        $mail->send();

        // SweetAlert success message
        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Registration Successful',
                text: 'You are successfully registered. Please verify your account first to login.',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'thankyou.php';
                }
            });
        </script>";
    } else {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Something went wrong. Please try again.',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'thankyou.php';
                }
            });
        </script>";
    }
}
?>

<!--Javascript for check email availability-->
<script>
    function checkAvailability() {
        $("#loaderIcon").show();
        jQuery.ajax({
            url: "check_availability.php",
            data: 'emailid=' + $("#email").val(),
            type: "POST",
            success: function(data) {
                $("#user-availability-status").html(data);
                $("#loaderIcon").hide();
            },
            error: function() {}
        });
    }
</script>

<!-- Registration Form -->
<section>
    <div class="modal-body modal-spa">
        <div class="login-grids">
            <div class="login">
                <div class="login-right">
                    <form method="post" id="register-form">
                        <h3>Create your account</h3>
                        <input type="text" placeholder="First Name" id="fname" name="fname" autocomplete="off" required="" oninput="validateName()">
                        <span id="fname-error" style="color:red;font-size:12px;"></span>
                        <input type="text" placeholder="Last Name" id="lname" name="lname" autocomplete="off" required="" oninput="validateName()">
                        <span id="lname-error" style="color:red;font-size:12px;"></span>
                        <input type="text" placeholder="Mobile number" onkeyup="this.value=this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1')" maxlength="11" name="mobilenumber" autocomplete="off" required="">
                        <input type="text" placeholder="Email id" name="email" id="email" onBlur="checkAvailability()" autocomplete="off" required="">
                        <span id="user-availability-status" style="font-size:12px;"></span>
                        <div style="position: relative;">
                            <input 
                                type="password" 
                                name="password" 
                                id="password" 
                                placeholder="Password" 
                                minlength="8" 
                                required 
                                oninput="validatePassword()"
                            >
                            <i class="fa fa-eye" id="show-pass" style="position: absolute; top: 0; right: 0; margin: 35px 10px 0 0; cursor: pointer;"></i>
                        </div>
                        <span id="password-criteria" style="font-size: 12px; color: red;"></span>
                        <div id="html_element"></div>
                        <input type="submit" name="submit_register" id="submit" value="CREATE ACCOUNT" disabled>
                    </form>
                </div>
                <div class="clearfix"></div>
            </div>
            <p>By logging in you agree to our <a href="page.php?type=terms">Terms and Conditions</a> and <a href="page.php?type=privacy">Privacy Policy</a></p>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function validatePassword() {
    const password = document.getElementById('password').value;
    const submitButton = document.getElementById('submit');
    const passwordCriteria = document.getElementById('password-criteria');
    const strongPasswordRegex = /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

    if (!strongPasswordRegex.test(password)) {
        passwordCriteria.textContent = "Password must be at least 8 characters long, include one uppercase letter, one lowercase letter, one number, and one special character.";
        passwordCriteria.style.color = "red";
        submitButton.disabled = true;
    } else {
        passwordCriteria.textContent = "Password is strong.";
        passwordCriteria.style.color = "green";
        submitButton.disabled = false;
    }
}

function validateName() {
    const fname = document.getElementById('fname').value;
    const lname = document.getElementById('lname').value;
    const fnameError = document.getElementById('fname-error');
    const lnameError = document.getElementById('lname-error');
    const submitButton = document.getElementById('submit');
    let valid = true;

    if (/\d/.test(fname)) {
        fnameError.textContent = 'First name should not contain numbers.';
        valid = false;
    } else {
        fnameError.textContent = '';
    }

    if (/\d/.test(lname)) {
        lnameError.textContent = 'Last name should not contain numbers.';
        valid = false;
    } else {
        lnameError.textContent = '';
    }

    submitButton.disabled = !valid;
}
</script>

<script type="text/javascript">
    var onloadCallback = function() {
        grecaptcha.render('html_element', {
            'sitekey' : '6LeBZG0qAAAAAHpE8Nr7ZxDcFQw3dVdkeJ4p3stl'
        });
    };

    const showPass = document.getElementById('show-pass');
    const passwordField = document.getElementById('password');

    showPass.addEventListener('click', () => {
        const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordField.setAttribute('type', type);

        // Toggle the eye icon
        if (type === 'password') {
            showPass.classList.remove('fa-eye-slash');
            showPass.classList.add('fa-eye');
        } else {
            showPass.classList.remove('fa-eye');
            showPass.classList.add('fa-eye-slash');
        }
    });
</script>

<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>

<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require "./phpmailer/src/Exception.php";
require "./phpmailer/src/PHPMailer.php";
require "./phpmailer/src/SMTP.php";

error_reporting(E_ALL);

if (isset($_POST['submit_register'])) {
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];

    // Backend validation for names
    if (!preg_match("/^[A-Za-z\s]+$/", $fname) || !preg_match("/^[A-Za-z\s]+$/", $lname)) {
        echo "<script>alert('First and last name should not contain numbers.');</script>";
    } else {
        $verification = uniqid() . rand(100, 999999999);
        $full = $fname . ' ' . $lname;
        $mnumber = $_POST['mobilenumber'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        // Database query
        $sql = "INSERT INTO tblusers (FullName, fname, lname, MobileNumber, EmailId, Password, Verification) 
                VALUES (:full, :fname, :lname, :mnumber, :email, :password, :verification)";
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
            // Send email
            $mail = new PHPMailer(true);
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'your-email@gmail.com';
            $mail->Password = 'your-email-password';
            $mail->Port = 587;

            $mail->setFrom('noreply@yourdomain.com', 'Your App Name');
            $mail->addAddress($email);
            $mail->Subject = "Email Account Verification";
            $mail->Body = "Click this link to verify your account: https://yourdomain.com/verify-account.php?verification=$verification&email=$email";

            $mail->send();

            echo "<script>window.location.href = 'thankyou.php';</script>";
        } else {
            echo "<script>alert('Something went wrong. Please try again.');</script>";
        }
    }
}
?>

<!-- Frontend Form -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <section>
                <div class="modal-body modal-spa">
                    <div class="login-grids">
                        <div class="login">
                            <div class="login-right">
                                <form method="post">
                                    <h3>Create your account</h3>
                                    <input type="text" placeholder="First Name" name="fname" autocomplete="off" required="" onkeyup="validateNames()">
                                    <input type="text" placeholder="Last Name" name="lname" autocomplete="off" required="" onkeyup="validateNames()">
                                    <span id="name-validation-message" style="color: red; font-size: 12px;"></span>
                                    <input type="text" placeholder="Mobile number" onkeyup="this.value=this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1')" maxlength="11" name="mobilenumber" autocomplete="off" required="">
                                    <input type="text" placeholder="Email id" name="email" id="email" onBlur="checkAvailability()" autocomplete="off" required="">
                                    <span id="user-availability-status" style="font-size:12px;"></span>
                                    <div style="position: relative;">
                                        <input type="password" name="password" id="password" placeholder="Password" value="" minlength="8" required>
                                        <i class="fa fa-eye" id="show-pass" style="position: absolute; top: 0; right: 0; margin: 35px 10px 0 0;"></i>
                                    </div>
                                    <div id="html_element"></div>
                                    <input type="submit" name="submit_register" id="submit" value="CREATE ACCOUNT">
                                </form>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <p>By logging in you agree to our <a href="page.php?type=terms">Terms and Conditions</a> and <a href="page.php?type=privacy">Privacy Policy</a></p>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>

<!-- JavaScript for Validation -->
<script>
    function validateNames() {
        const fname = document.querySelector('input[name="fname"]').value;
        const lname = document.querySelector('input[name="lname"]').value;
        const submitButton = document.getElementById('submit');
        const nameValidationMessage = document.getElementById('name-validation-message');

        const namePattern = /^[A-Za-z\s]+$/; // Allows letters and spaces only

        if (!namePattern.test(fname) || !namePattern.test(lname)) {
            nameValidationMessage.textContent = "First and last name should not contain numbers.";
            submitButton.disabled = true;
        } else {
            nameValidationMessage.textContent = "";
            submitButton.disabled = false;
        }
    }

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

<!-- reCAPTCHA -->
<script type="text/javascript">
    var onloadCallback = function() {
        grecaptcha.render('html_element', {
            'sitekey': '6LezNpMqAAAAAJo_vbJQ6Lo10T2GxhtxeROWoB8p'
        });
    };
</script>
<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>

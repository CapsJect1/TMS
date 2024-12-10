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
    $mnumber = $_POST['mobilenumber'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Backend validation for names
    if (!preg_match("/^[A-Za-z\s]+$/", $fname) || !preg_match("/^[A-Za-z\s]+$/", $lname)) {
        echo "<script>alert('First and last name should not contain numbers.');</script>";
    } else {
        $verification = uniqid() . rand(100, 999999999);
        $full = $fname . ' ' . $lname;

        // Insert data into database
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
            // Send verification email
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'your_email@gmail.com';
            $mail->Password = 'your_app_password';
            $mail->Port = 587;

            $mail->setFrom('your_email@gmail.com', 'Your Site Name');
            $mail->addAddress($email);
            $mail->Subject = "Email Account Verification";
            $mail->Body = "Click this link to verify account: https://yourdomain.com/verify-account.php?verification=$verification&email=$email";
            
            if ($mail->send()) {
                echo "<script>window.location.href = 'thankyou.php';</script>";
            } else {
                echo "<script>alert('Failed to send verification email.');</script>";
            }
        } else {
            echo "<script>alert('Something went wrong. Please try again.');</script>";
        }
    }
}
?>

<!-- HTML FORM -->
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
                                <form method="post">
                                    <h3>Create your account</h3>
                                    <input type="text" value="" placeholder="First Name" name="fname" autocomplete="off" required onkeyup="validateNames()">
                                    <input type="text" value="" placeholder="Last Name" name="lname" autocomplete="off" required onkeyup="validateNames()">
                                    <input type="text" value="" placeholder="Mobile number" onkeyup="this.value=this.value.replace(/[^0-9]/g, '')" maxlength="11" name="mobilenumber" autocomplete="off" required>
                                    <input type="email" value="" placeholder="Email ID" name="email" id="email" onblur="checkAvailability()" autocomplete="off" required>
                                    <span id="user-availability-status" style="font-size: 12px;"></span>
                                    <div style="position: relative;">
                                        <input type="password" name="password" id="password" placeholder="Password" minlength="8" required>
                                    </div>
                                    <span id="name-validation-message" style="color: red; font-size: 12px;"></span>
                                    <input type="submit" name="submit_register" id="submit" value="CREATE ACCOUNT" disabled>
                                </form>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <p>By logging in you agree to our <a href="terms.php">Terms and Conditions</a> and <a href="privacy.php">Privacy Policy</a>.</p>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
function validateNames() {
    const fname = document.querySelector('input[name="fname"]').value;
    const lname = document.querySelector('input[name="lname"]').value;
    const submitButton = document.getElementById('submit');
    const nameValidationMessage = document.getElementById('name-validation-message');

    const namePattern = /^[A-Za-z\s]+$/; // Only letters and spaces

    if (!namePattern.test(fname) || !namePattern.test(lname)) {
        nameValidationMessage.textContent = "First and last name should not contain numbers.";
        submitButton.disabled = true;
    } else {
        nameValidationMessage.textContent = "";
        submitButton.disabled = false;
    }
}
</script>

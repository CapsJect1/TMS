
<?php
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

    if (strlen($password) < 8) {
        die("Password must be at least 8 characters long.");
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
        $_SESSION['msg'] = "You are successfully registered. Please verify your account first to login.";

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

        echo "<script>window.location.href = 'thankyou.php';</script>";
    } else {
        $_SESSION['msg'] = "Something went wrong. Please try again.";
        echo "<script>window.location.href = 'thankyou.php';</script>";
    }
}
?>


<!--Javascript for check email availabilty-->
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


                              <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>
</head>
<body class="bg-light">
<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="card shadow-lg" style="width: 100%; max-width: 500px;">
        <div class="card-body">
            <h3 class="card-title text-center mb-4">Create Your Account</h3>
            <form method="post" id="register-form">
                <div class="mb-3">
                    <label for="fname" class="form-label">First Name</label>
                    <input type="text" class="form-control" id="fname" name="fname" placeholder="First Name" required oninput="validateName()">
                    <span id="fname-error" class="text-danger small"></span>
                </div>

                <div class="mb-3">
                    <label for="lname" class="form-label">Last Name</label>
                    <input type="text" class="form-control" id="lname" name="lname" placeholder="Last Name" required oninput="validateName()">
                    <span id="lname-error" class="text-danger small"></span>
                </div>

                <div class="mb-3">
                    <label for="mobilenumber" class="form-label">Mobile Number</label>
                    <input type="text" class="form-control" id="mobilenumber" name="mobilenumber" placeholder="Mobile Number" maxlength="11" required onkeyup="this.value=this.value.replace(/[^0-9]/g, '')">
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Email Address" required onblur="checkAvailability()">
                    <span id="user-availability-status" class="small"></span>
                </div>

                <div class="mb-3 position-relative">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" minlength="8" required>
                    <i class="fa fa-eye position-absolute" id="show-pass" style=" cursor: pointer;"></i>
                </div>

                <div class="mb-3" id="html_element"></div>

                <button type="submit" class="btn btn-primary w-100" name="submit_register" id="submit" disabled>CREATE ACCOUNT</button>
            </form>
        </div>
    </div>
</div>
                       

<script>
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


<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit"
        async defer>
    </script>

<script>
	// let showPass = document.getElementById('show-pass');
    // showPass.onclick = () => {
    //     let passwordInp = document.forms['signup']['password'];
    //     if (passwordInp.getAttribute('type') == 'password') {
    //         showPass.classList.replace('fa-eye', 'fa-eye-slash')
            
    //         passwordInp.setAttribute('type', 'text')
    //     }else{
    //         showPass.classList.replace('fa-eye-slash', 'fa-eye')
    //         passwordInp.setAttribute('type', 'password')
    //     }
    // }
</script>

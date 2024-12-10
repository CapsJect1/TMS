<?php
session_start();

if (isset($_SESSION['ERROR_LOGIN'])) {
    if ($_SESSION['date'] == date('Y-m-d')) {
        unset($_SESSION['ERROR_LOGIN']);
    }
}

if (isset($_POST['signin'])) {
    // Check if the user is locked out
    if (isset($_SESSION['ERROR_LOGIN']) && $_SESSION['ERROR_LOGIN']['count'] >= 3) {
        echo "<script>
        Swal.fire({
            title: 'Error!',
            text: 'Login trial expired, please try again later',
            icon: 'error',
            showConfirmButton: true,
            timer: 60000,
            timerProgressBar: true
        });
        </script>";
        echo "<script>window.location.href = 'index.php';</script>";
    }

    // Google reCAPTCHA verification
    $recaptcha_secret = '6LezNpMqAAAAAKA-tks15YZHfdpFeWhQZo2kj-gb';
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
                showConfirmButton: true,
                timer: 60000,
                timerProgressBar: true
            });
            </script>";
        echo "<script>window.location.href = 'index.php';</script>";
    }

    $email = htmlspecialchars(stripslashes(trim($_POST['email'])));
    $password = htmlspecialchars(stripslashes(trim($_POST['password'])));
    $status = 2;

    $sql = "SELECT id, FullName, EmailId, fname, lname, Password, Status, login_attempts, lock_time FROM tblusers WHERE EmailId=:email AND Status = :stat";
    $query = $dbh->prepare($sql);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->bindParam(':stat', $status, PDO::PARAM_INT);
    $query->execute();
    $user = $query->fetch(PDO::FETCH_ASSOC);

    if ($query->rowCount() > 0) {
        if ($user['Status'] == 2) {
            if ($user['lock_time'] && strtotime($user['lock_time']) > time()) {
                $time_left = strtotime($user['lock_time']) - time();
                echo "<script>
                    Swal.fire({
                        title: 'Error!',
                        text: 'Account locked. Try again in " . ceil($time_left / 60) . " minutes.',
                        icon: 'error',
                        showConfirmButton: true,
                        timer: 60000,
                        timerProgressBar: true
                    });
                    </script>";
                echo "<script>window.location.href = 'index.php';</script>";
                exit;
            }

            if (password_verify($password, $user['Password'])) {
                $sql_update = "UPDATE tblusers SET login_attempts = 0, lock_time = NULL WHERE EmailId = :email";
                $update_query = $dbh->prepare($sql_update);
                $update_query->bindParam(':email', $email, PDO::PARAM_STR);
                $update_query->execute();

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['FullName'];
                $_SESSION['login'] = $user['EmailId'];
                $_SESSION['fname'] = $user['fname'];
                $_SESSION['lname'] = $user['lname'];
                echo "<script>window.location.href = 'package-list.php';</script>";
            } else {
                $sql_update = "UPDATE tblusers SET login_attempts = login_attempts + 1 WHERE EmailId = :email";
                $update_query = $dbh->prepare($sql_update);
                $update_query->bindParam(':email', $email, PDO::PARAM_STR);
                $update_query->execute();

                if ($user['login_attempts'] + 1 >= 3) {
                    $lock_time = date('Y-m-d H:i:s', strtotime('+5 minutes'));
                    $sql_lock = "UPDATE tblusers SET lock_time = :lock_time WHERE EmailId = :email";
                    $lock_query = $dbh->prepare($sql_lock);
                    $lock_query->bindParam(':lock_time', $lock_time, PDO::PARAM_STR);
                    $lock_query->bindParam(':email', $email, PDO::PARAM_STR);
                    $lock_query->execute();

                    echo "<script>
                        Swal.fire({
                            title: 'Error!',
                            text: 'Too many failed attempts. Please try again in 5 minutes.',
                            icon: 'error',
                            showConfirmButton: true,
                            timer: 60000,
                            timerProgressBar: true
                        });
                        </script>";
                } else {
                    echo "<script>
                        Swal.fire({
                            title: 'Error!',
                            text: 'Incorrect email or password',
                            icon: 'error',
                            showConfirmButton: true,
                            timer: 60000,
                            timerProgressBar: true
                        });
                        </script>";
                }
                echo "<script>window.location.href = 'index.php';</script>";
            }
        } else {
            echo "<script>
            Swal.fire({
                title: 'Error!',
                text: 'Please confirm your account first',
                icon: 'error',
                showConfirmButton: true,
                timer: 60000,
                timerProgressBar: true
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
                showConfirmButton: true,
                timer: 60000,
                timerProgressBar: true
            });
            </script>";
        echo "<script>window.location.href = 'index.php';</script>";
    }
}
?>

<!DOCTYPE HTML>
<html>

<head>
	<title>TMS | Tourism Management System</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

	<script
		type="applijewelleryion/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
	<link href="css/bootstrap.css" rel='stylesheet' type='text/css' />
	<link href="css/style.css" rel='stylesheet' type='text/css' />
	<link href='//fonts.googleapis.com/css?family=Open+Sans:400,700,600' rel='stylesheet' type='text/css'>
	<link href='//fonts.googleapis.com/css?family=Roboto+Condensed:400,700,300' rel='stylesheet' type='text/css'>
	<link href='//fonts.googleapis.com/css?family=Oswald' rel='stylesheet' type='text/css'>
	<link href="css/font-awesome.css" rel="stylesheet">
	<!-- Custom Theme files -->
	<script src="js/jquery-1.12.0.min.js"></script>
	<script src="js/sweet_alert.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<!--animate-->
	<link href="css/animate.css" rel="stylesheet" type="text/css" media="all">
	<script src="js/wow.min.js"></script>
	<script>
		new WOW().init();
	</script>
	<!--//end-animate-->
	    <script
        type="application/javascript">
        addEventListener("load", function () { setTimeout(hideURLbar, 0); }, false);

        function hideURLbar() {
            window.scrollTo(0, 1);
        }

        // Disable right-click context menu
        document.addEventListener('contextmenu', function (e) {
            e.preventDefault();  // Prevent the default context menu
        });
    </script>
</head>

<body>
    <div class="container">
        <form method="post" name="login">
            <h3>Sign in with your account</h3>
            <input type="text" name="email" id="email" placeholder="Enter your Email" required>
            <div style="position: relative;">
                <input type="password" name="password" id="password" placeholder="Password" required>
                <i class="fa fa-eye" id="show-pass2"></i>
            </div>
            <h4><a href="forgot-password.php">Forgot password</a></h4>
            <div class="g-recaptcha" data-sitekey="6LezNpMqAAAAAJo_vbJQ6Lo10T2GxhtxeROWoB8p"></div>
            <input type="submit" name="signin" value="SIGN IN">
        </form>
    </div>
    <script>
        let showPass2 = document.getElementById('show-pass2');
        showPass2.onclick = () => {
            let passwordInp = document.forms['login']['password'];
            if (passwordInp.getAttribute('type') === 'password') {
                showPass2.classList.replace('fa-eye', 'fa-eye-slash');
                passwordInp.setAttribute('type', 'text');
            } else {
                showPass2.classList.replace('fa-eye-slash', 'fa-eye');
                passwordInp.setAttribute('type', 'password');
            }
        }
    </script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</body>


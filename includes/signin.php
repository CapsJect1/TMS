<?php

if (isset($_POST['signin'])) {
	$email = htmlspecialchars(stripslashes(trim($_POST['email'])));
	$password = htmlspecialchars(stripslashes(trim($_POST['password'])));
	$status = 2;
	$captchaResponse = $_POST['g-recaptcha-response']; // Get the CAPTCHA response

	// Verify CAPTCHA with Google
	$secretKey = "YOUR_SECRET_KEY_HERE";  // Replace with your reCAPTCHA secret key
	$verifyURL = "https://www.google.com/recaptcha/api/siteverify";
	$response = file_get_contents($verifyURL . "?secret=" . $secretKey . "&response=" . $captchaResponse);
	$responseKeys = json_decode($response, true);

	if(intval($responseKeys["success"]) !== 1) {
		// CAPTCHA validation failed
		echo "<script>
				Swal.fire({
					title: 'Error!',
					text: 'Please complete the CAPTCHA to sign in.',
					icon: 'error',
					timer: 1500,
					showConfirmButton: false
				});
			  </script>";
	} else {
		// CAPTCHA was successful
		// SQL query to fetch user details based on email and password
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
					// Redirect to a dashboard or home page after successful login
					?>
					<script>
						window.location.href = "package-list.php"
					</script>
					<?php
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
						<div class="login-right">
							<form method="post" name="login">
    <h3>Sign in with your account</h3>
    <input type="text" name="email" id="email" placeholder="Enter your Email" required=""/>
    <div style="position: relative;">
        <input type="password" name="password" id="password" placeholder="Password" value="" required=""/>
        <i class="fa fa-eye" id="show-pass2" style="position: absolute; top: 0; right: 0; margin: 35px 10px 0 0;"></i>
    </div>
    <h4><a href="forgot-password.php">Forgot password</a></h4>
    
    <!-- CAPTCHA widget will be rendered here -->
    <div id="html_element"></div>
    
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
    };
</script>

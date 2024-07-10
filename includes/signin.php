<?php
session_start();

if (isset($_POST['signin'])) {
	$email = $_POST['email'];
	$password = md5($_POST['password']); // Note: MD5 hashing is used here for simplicity; consider using more secure hashing methods

	// SQL query to fetch user details based on email and password
	$sql = "SELECT id, FullName, EmailId FROM tblusers WHERE EmailId=:email AND Password=:password";
	$query = $dbh->prepare($sql);
	$query->bindParam(':email', $email, PDO::PARAM_STR);
	$query->bindParam(':password', $password, PDO::PARAM_STR);
	$query->execute();
	$user = $query->fetch(PDO::FETCH_ASSOC);

	if ($user) {
		// Set session variables upon successful login
		$_SESSION['user_id'] = $user['id'];
		$_SESSION['user_name'] = $user['FullName'];
		$_SESSION['login'] = $user['EmailId'];

		// Redirect to a dashboard or home page after successful login
		// header("Location: package-list.php");\
		?>
		<script>
			window.location.href = "package-list.php"
		</script>
		<?php 
		exit;
	} else {
		echo "<script>alert('Invalid Email or Password');</script>";
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
							<form method="post">
								<h3>Sign in with your account</h3>
								<input type="text" name="email" id="email" placeholder="Enter your Email" required="">
								<input type="password" name="password" id="password" placeholder="Password" value=""
									required="">
								<h4><a href="forgot-password.php">Forgot password</a></h4>
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
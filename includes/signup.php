<?php
// error_reporting(0);
if (isset($_POST['submit'])) {
	$fname = $_POST['fname'];
	$lname = $_POST['lname'];
	$full = $fname . ' ' . $lname; 
	$mnumber = $_POST['mobilenumber'];
	$email = $_POST['email'];
	$password = md5($_POST['password']);
	$sql = "INSERT INTO  tblusers(FullName,fname,lname,MobileNumber,EmailId,Password) VALUES(:full,:fname, :lname,:mnumber,:email,:password)";
	$query = $dbh->prepare($sql);
	$query->bindParam(':full', $full, PDO::PARAM_STR);
	$query->bindParam(':fname', $fname, PDO::PARAM_STR);
	$query->bindParam(':lname', $lname, PDO::PARAM_STR);
	$query->bindParam(':mnumber', $mnumber, PDO::PARAM_STR);
	$query->bindParam(':email', $email, PDO::PARAM_STR);
	$query->bindParam(':password', $password, PDO::PARAM_STR);
	$query->execute();
	$lastInsertId = $dbh->lastInsertId();
	if (strlen($_POST['password']) >= 8) {
		if ($lastInsertId) {
			$_SESSION['msg'] = "You are Scuccessfully registered. Now you can login ";
			header('location:thankyou.php');
		} else {
			$_SESSION['msg'] = "Something went wrong. Please try again.";
			header('location:thankyou.php');
		}
	}else{
		
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
								<form name="signup" method="post">
									<h3>Create your account </h3>


									<input type="text" value="" placeholder="First Name" name="fname" autocomplete="off" required="">
									<input type="text" value="" placeholder="Last Name" name="lname" autocomplete="off" required="">
									<input type="text" value="" placeholder="Mobile number" maxlength="10" name="mobilenumber" autocomplete="off" required="">
									<input type="text" value="" placeholder="Email id" name="email" id="email" onBlur="checkAvailability()" autocomplete="off" required="">
									<span id="user-availability-status" style="font-size:12px;"></span>
									<div style="position: relative;">
								<input type="password" name="password" id="password" placeholder="Password" value="" minlength="8"
									required>
									<i class="fa fa-eye" id="show-pass" style="position: absolute; top: 0; right: 0; margin: 35px 10px 0 0;"></i>
								</div>
									<input type="submit" name="submit" id="submit" value="CREATE ACCOUNT">
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

<script>
	let showPass = document.getElementById('show-pass');
    showPass.onclick = () => {
        let passwordInp = document.forms['signup']['password'];
        if (passwordInp.getAttribute('type') == 'password') {
            showPass.classList.replace('fa-eye', 'fa-eye-slash')
            
            passwordInp.setAttribute('type', 'text')
        }else{
            showPass.classList.replace('fa-eye-slash', 'fa-eye')
            passwordInp.setAttribute('type', 'password')
        }
    }
</script>
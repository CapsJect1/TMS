<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;



require "./phpmailer/src/Exception.php";
require "./phpmailer/src/PHPMailer.php";
require "./phpmailer/src/SMTP.php";
// error_reporting(0);
if (isset($_POST['submit'])) {
	$verification = uniqid() . rand(100, 999999999);
	$fname = $_POST['fname'];
	$lname = $_POST['lname'];
	$full = $fname . ' ' . $lname; 
	$mnumber = $_POST['mobilenumber'];
	$email = $_POST['email'];
	$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
	$sql = "INSERT INTO  tblusers(FullName,fname,lname,MobileNumber,EmailId,Password,Verification) VALUES(:full,:fname, :lname,:mnumber,:email,:password,:verification)";
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
	if (strlen($_POST['password']) >= 8) {
		if ($lastInsertId) {
			$_SESSION['msg'] = "You are Scuccessfully registered. Please verify your account first to login";
			// header('location:thankyou.php');

			$mail = new PHPMailer(true);
			$mail->SMTPDebug = 0;
			$mail->isSMTP();
			$mail->Host = 'smtp.gmail.com';
			$mail->SMTPAuth = true;
			$mail->Username = 'sshin8859@gmail.com';
			$mail->Password = 'hhgwbzklpinejqjh';
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
			?>
			<script>
				window.location.href = "thankyou.php"
			</script>
			<?php 
		} else {
			$_SESSION['msg'] = "Something went wrong. Please try again.";
			// header('location:thankyou.php');
			?>
			<script>
				window.location.href = "thankyou.php"
			</script>
			<?php 
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
					<div class="login-grids "> 
						<div class="login">

							<div class="login-right">
								<form name="signup" method="post">
									<h3>Create your account </h3>
									<input type="text" value="" placeholder="First Name" name="fname" autocomplete="off" required="">
									<input type="text" value="" placeholder="Last Name" name="lname" autocomplete="off" required="">
									<input type="text" value="" placeholder="Mobile number" onkeyup="this.value=this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1')" maxlength="11" name="mobilenumber" autocomplete="off" required="">
									<input type="text" value="" placeholder="Email id" name="email" id="email" onBlur="checkAvailability()" autocomplete="off" required="">
									<span id="user-availability-status" style="font-size:12px;"></span>
									<div style="position: relative;">
								<input type="password" name="password" id="password" placeholder="Password" value="" minlength="8"
									required>
									<i class="fa fa-eye" id="show-pass" style="position: absolute; top: 0; right: 0; margin: 35px 10px 0 0;"></i>
								</div>

								<div id="html_element"></div>

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

<script type="text/javascript">
      var onloadCallback = function() {
        grecaptcha.render('html_element', {
          'sitekey' : '6LeBZG0qAAAAAHpE8Nr7ZxDcFQw3dVdkeJ4p3stl'
        });
      };
    </script>


<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit"
        async defer>
    </script>

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

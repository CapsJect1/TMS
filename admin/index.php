<?php
// echo password_hash('johnreyalo@@2024', PASSWORD_DEFAULT);
session_start();
include('includes/config.php');
if (isset($_POST['login'])) {
	$uname = htmlspecialchars(stripslashes(trim($_POST['username'])));
	$password = htmlspecialchars(stripslashes(trim($_POST['password'])));
	$sql = "SELECT UserName,Password,Name,EmailId FROM admin WHERE UserName=:uname";
	$query = $dbh->prepare($sql);
	$query->bindParam(':uname', $uname, PDO::PARAM_STR);
	// $query->bindParam(':password', $password, PDO::PARAM_STR);
	$query->execute();
	$results = $query->fetch(PDO::FETCH_OBJ);
	if ($query->rowCount() > 0) {
		if (password_verify($password, $results->Password)) {
			$_SESSION['alogin'] = $uname;
			$_SESSION['name'] = $results->Name;
			$_SESSION['email'] = $results->EmailId;
			echo "<script type='text/javascript'> document.location = 'https://santafeport.com/admin/dashboard.php'; </script>";
		} else {
			?>
			<script>
				document.addEventListener("DOMContentLoaded", () => {
					Swal.fire({
						title: 'Error!',
						text: 'Incorrect email or password',
						icon: 'error',
						timer: 1500,
						showConfirmButton: false
					});
				})
			</script>
				<?php
		}
	} else {

		?>
			<script>
				document.addEventListener("DOMContentLoaded", () => {
					Swal.fire({
						title: 'Error!',
						text: 'Incorrect email or password',
						icon: 'error',
						timer: 1500,
						showConfirmButton: false
					});
				})
			</script>
				<?php
	}
}

?>

<!DOCTYPE HTML>
<html>

<head>
	<title>TMS | Admin Sign in</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="../css/font-awesome.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
		integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
		integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<script src="js/sweet_alert.js"></script>
	<script
		type="application/x-javascript">
		addEventListener("load", function() {
			setTimeout(hideURLbar, 0);
		}, false);

		function hideURLbar() {
			window.scrollTo(0, 1);
		}
	</script>
	
</head>

<style>
	.container {
			height: 100vh;
		}
			body {
		background: url(../images/santa1.jpg)no-repeat;
		background-size: cover;
		-webkit-background-size: cover;
		-o-background-size: cover;
		-ms-background-size: cover;
		-moz-background-size: cover;
		min-height: 700px;
	}
	.text-primary {
		color: #38AF05 !important;

	}
	.bg {
		background-color: #38AF05 !important;
	
	}
	svg {
		background-color: #38AF05 !important;

	}
	.btn-primary1 {
		background-color: #38AF05 !important;
		color: white;
	}
</style>







<div class="container justify-content-center align-items-center d-flex ">
	<div class="">
   <div class="card p-4">
	<form method="post" name="login" style="max-width: 600px; width: 100%;">
		<a  href="../index.php" class="bg">
			<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#38AF05" class="bi bi-arrow-left" viewBox="0 0 16 16">
				<path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
			</svg>
		</a>
		<h1 class="mb-4 text-primary text-center">
			Sign in
		</h1>
        <input type="text" class="form-control mb-3" name="username"  placeholder="Enter username">
		<div class="password-agileits">
			<div style="position: relative;">
				<input type="password" name="password" id="password" class="form-control" placeholder="Password" value=""
					required="">
				<i class="fa fa-eye" id="show-pass" style="position: absolute; top: 0; right: 0; margin: 10px 10px 0 0;"></i>
			</div>
			<div class="clearfix"></div>
		</div>

		<div class="row">
		<div class="col-12">
			<a href="forgot-password.php" class="btn ">Forgot Password</a>
		</div>	

		<div class="col-12">
			<input type="submit" class="btn btn-primary1 mt-2 form-control" name="login"
			value="Sign In">
		</div>
		</div>
    </form>
   </div>
	</div>

</div>

	
<script>
	let showPass = document.getElementById('show-pass');
	showPass.onclick = () => {
		let passwordInp = document.forms['login']['password'];
		if (passwordInp.getAttribute('type') == 'password') {
			showPass.classList.replace('fa-eye', 'fa-eye-slash')

			passwordInp.setAttribute('type', 'text')
		} else {
			showPass.classList.replace('fa-eye-slash', 'fa-eye')
			passwordInp.setAttribute('type', 'password')
		}
	}
</script>

</body>

</html>

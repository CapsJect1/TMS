<?php
session_start();
include ('includes/config.php');
if (isset($_POST['login'])) {
	$uname = $_POST['username'];
	$password = md5($_POST['password']);
	$sql = "SELECT UserName,Password,Name,EmailId FROM admin WHERE UserName=:uname and Password=:password";
	$query = $dbh->prepare($sql);
	$query->bindParam(':uname', $uname, PDO::PARAM_STR);
	$query->bindParam(':password', $password, PDO::PARAM_STR);
	$query->execute();
	$results = $query->fetch(PDO::FETCH_OBJ);
	if ($query->rowCount() > 0) {
		$_SESSION['alogin'] = $_POST['username'];
		$_SESSION['name'] = $results->Name;
		$_SESSION['email'] = $results->EmailId;
		
		echo "<script type='text/javascript'> document.location = 'dashboard.php'; </script>";
	} else {

		echo "<script>alert('Invalid Details');</script>";

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
	<script
		type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
	<style>
		.container{
			height: 100vh;
		}
	</style>
</head>

    <style>
        body { background: url(../images/santa1.jpg)no-repeat;
    background-size: cover;
    -webkit-background-size: cover;
    -o-background-size: cover;
    -ms-background-size: cover;
    -moz-background-size: cover;
    min-height: 700px;
        }
    </style>



	<div class="container d-flex justify-content-center align-items-center">
		<div class="col-7">
			<div class="card">
				<div class="card-header text-center bg-primary p-5">
					<h2>Sign In</h2>
				</div>


				<div class="card-body">

					<form method="post" name="login">
						<div class="username">
							<!-- <span class="username">Username:</span> -->
							<input type="text" name="username" class="form-control" placeholder="Enter username"
								required>
							<div class="clearfix"></div>
						</div>



						<br>
						<div class="password-agileits">
							<!-- <span class="username">Password:</span> -->
							<div style="position: relative;">
								<input type="password" name="password" id="password" class="form-control" placeholder="Password" value=""
									required="">
									<i class="fa fa-eye" id="show-pass" style="position: absolute; top: 0; right: 0; margin: 10px 10px 0 0;"></i>
								</div>
							<div class="clearfix"></div>
						</div>


						<a href="forgot-password.php" class="btn btn-link">Forgot Password</a>

						<div class="row">
							<div class="col-6">
								<input type="submit" class="btn btn-primary mt-2 form-control" name="login"
									value="Sign In">

							</div>
							<div class="col-6">
								<a href="../index.php" class="btn btn-secondary mt-2 form-control">Back</a>

							</div>
						</div>




					</form>
				</div>
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
        }else{
            showPass.classList.replace('fa-eye-slash', 'fa-eye')
            passwordInp.setAttribute('type', 'password')
        }
    }
</script>

</body>

</html>
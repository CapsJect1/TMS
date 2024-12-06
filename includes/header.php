<style>
	a {
		color: black !important;
		text-decoration: none;
	}

	footer {
		background: #212529;
	}
</style>

<?php 
	if (isset($_SESSION['user_id'])) {
		$user_id = clean($_SESSION['user_id']);
		$get_books_payment = $dbh->query("SELECT * FROM `booking` WHERE status ='payment' AND user_id = '$user_id'");
	}else{
		$get_books_payment = 0;
	}
?>

<?php if (isset($_SESSION['login'])) { ?>
	<div class="top-header dont-print">
		<div class="container">
			<ul class="tp-hd-lft wow fadeInLeft animated" data-wow-delay=".5s">
				<li class="hm"><a href="index.html"><i class="fa fa-home"></i></a></li>
				<li class="prnt"><a href="profile.php">My Profile</a></li>
				<li class="prnt"><a href="change-password.php">Change Password</a></li>
				<!-- <li class="prnt"><a href="tour-history.php">My Tour History</a></li> -->
				<li class="prnt"><a href="issuetickets.php">Raised Tickets</a></li>
			</ul>
			<ul class="tp-hd-rgt wow fadeInRight animated" data-wow-delay=".5s">
				<li class="tol">

				<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="modal" data-target="#show-books"><i class="fa fa-bell"></i> <?= $get_books_payment->rowCount() ?> <i class="fa fa-caret-down"></i></a>
				</li>
				<li class="tol">Welcome :</li>
				<li class="sig"><?php echo htmlentities($_SESSION['login']); ?></li>
				<li class="sigi"><a href="logout.php">/ Logout</a></li>
			</ul>
			<div class="clearfix"></div>
		</div>
	</div><?php } else { ?>
	<div class="top-header dont-print">
		<div class="container">
			<ul class="tp-hd-lft wow fadeInLeft animated" data-wow-delay=".5s">
				<li class="hm"><a href="index.php"><i class="fa fa-home"></i></a></li>
<!-- 				<li class="btn btn-link"><a href="admin/">Admin Login</a></li> -->
			</ul>
			<ul class="tp-hd-rgt wow fadeInRight animated" data-wow-delay=".5s">
				<li class="tol">Contact Number : 0923202323</li>
				<li class="sig"><a href="#" data-toggle="modal" data-target="#myModal">Sign Up</a></li>
				<li class="sigi"><a href="#" data-toggle="modal" data-target="#myModal4">/ Sign In</a></li>
			</ul>
			<div class="clearfix"></div>
		</div>
	</div>
<?php } ?>
<!--- /top-header ---->
<!--- header ---->
<div class="header dont-print">
	<div class="container">
		<div class="logo wow fadeInDown animated" data-wow-delay=".5s">
			<a class="" href="index.php">Santa fe Port Tourist Environmental fee Collection And Staycation <span class="text-white">Management System</span></a>
		</div>

		<div class="lock fadeInDown animated" data-wow-delay=".5s">
			<li><i class="fa fa-lock"></i></li>
			<li>
				<div class="securetxt"><span class="badge bg-dark">SAFE &amp; SECURE</span> </div>
			</li>
			<div class="clearfix"></div>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
<!--- /header ---->
<!--- footer-btm ---->



<div class="container-fluid mb-2 dont-print">
	<nav class="navbar navbar-expand-sm bg-light">

		<div class="container-fluid">
			<!-- Links -->
			<ul class="navbar-nav">
				<ul class="nav navbar-nav">
					<li><a href="index.php">Home</a></li>
					<li><a href="page.php?type=aboutus">About</a></li>
					<li><a href="package-list.php">Packages</a></li>
					<li><a href="ticket.php">Get ticket to Santafe</a></li>

					<li><a href="page.php?type=privacy">Privacy Policy</a></li>
					<li><a href="page.php?type=terms">Terms of Use</a></li>
					<li><a href="page.php?type=contact">Contact Us</a></li>
					<?php if (isset($_SESSION['login'])) { ?>
						<li>Need Help?<a href="#" data-toggle="modal" data-target="#myModal3"> / Write Us </a> </li>
					<?php } else { ?>
						<li><a href="enquiry.php"> Enquiry </a> </li>
					<?php } ?>
					<div class="clearfix"></div>

				</ul>
			</ul>
		</div>

	</nav>
</div>


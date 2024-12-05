<?php
session_start();


include ('includes/config.php');
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
	<?php include ('includes/header.php'); ?>
	<div class="banner" style="position: relative;">
		<div style="width: 100%; position: absolute; top: 2%; left:0; padding: 0 40px;">
		<img src="./images/IMG_20240714_153433.jpg" style="height: 100px; width: 100%;">
		</div>
		
		<div class="container">
			<div class="row">
				
				<div class="col-md-12 text-center">
					<img src="./images/Santa_Fe_Cebu.png" alt="hero-img-logo">
					<h1 style="color: #ffff;">Welcome to Santa Fe</h1>
					<p style="color: #fff;">Book your package of choice. Book na Bhaii!!</p>
					<a href="package-list.php" class="btn btn-primary" style="color: #fff !important; margin-top: 15px;">Book Now</a>
				</div>
				<!-- <div class="col-md-6" >
				<img src="./images/port.webp" alt="hero-img-1">
				</div> -->
				
			</div>
		</div>
	</div>






	<!---holiday---->
	<div class="container">
		<div class="holiday">





			<h3>Package List</h3>


			<?php $sql = "SELECT * from tbltourpackages order by rand() limit 4";
			$query = $dbh->prepare($sql);
			$query->execute();
			$results = $query->fetchAll(PDO::FETCH_OBJ);
			$cnt = 1;
			if ($query->rowCount() > 0) {
				foreach ($results as $result) { ?>
					<div class="rom-btm">
						<div class="col-md-3 room-left wow fadeInLeft animated" data-wow-delay=".5s">
							<img src="admin/pacakgeimages/<?php echo clean($result->PackageImage); ?>"
								class="img-responsive" alt="">
						</div>
						<div class="col-md-6 room-midle wow fadeInUp animated" data-wow-delay=".5s">
							<h4>Package Name: <?php echo clean($result->PackageName); ?></h4>
							<h6>Package Type : <?php echo clean($result->PackageType); ?></h6>
							<p><b>Package Location :</b> <?php echo clean($result->PackageLocation); ?></p>
							<p><b>Features</b> <?php echo clean($result->PackageFetures); ?></p>
						</div>
						<div class="col-md-3 room-right wow fadeInRight animated" data-wow-delay=".5s">
							<h5>PHP <?php echo clean($result->PackagePrice); ?></h5>
							<a href="package-details.php?pkgid=<?php echo clean($result->PackageId); ?>"
								class="view">Details</a>
						</div>
						<div class="clearfix"></div>
					</div>

				<?php }
			} ?>


			<div><a href="package-list.php" class="view">View More Packages</a></div>
		</div>
		<div class="clearfix"></div>
	</div>



	<!--- routes ---->
	<div class="routes">
		<div class="container">
			<div class="col-md-4 routes-left wow fadeInRight animated" data-wow-delay=".5s">
				<div class="rou-left">
					<a href="#"><i class="glyphicon glyphicon-list-alt"></i></a>
				</div>
				<div class="rou-rgt wow fadeInDown animated" data-wow-delay=".5s">
					<h3>80000</h3>
					<p>Enquiries</p>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="col-md-4 routes-left">
				<div class="rou-left">
					<a href="#"><i class="fa fa-user"></i></a>
				</div>
				<div class="rou-rgt">
					<h3>1900</h3>
					<p>Registered users</p>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="col-md-4 routes-left wow fadeInRight animated" data-wow-delay=".5s">
				<div class="rou-left">
					<a href="#"><i class="fa fa-ticket"></i></a>
				</div>
				<div class="rou-rgt">
					<h3>7,00,00,000+</h3>
					<p>Booking</p>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="clearfix"></div>
		</div>
	</div>

	<?php include ('includes/footer.php'); ?>
	<!-- signup -->
	<?php include ('includes/signup.php'); ?>
	<!-- //signu -->
	<!-- signin -->
	<?php include ('includes/signin.php'); ?>
	<!-- //signin -->
	<!-- write us -->
	<?php include ('includes/write-us.php'); ?>
	<!-- //write us -->

	<script>
// document.addEventListener("DOMContentLoaded", function(){
//   const newUrl = '/';

// // Change the URL without refreshing the page
// history.pushState(null, '', newUrl);
// })
</script>
</body>

</html>

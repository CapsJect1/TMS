<?php
session_start();
include('includes/config.php');
if (strlen($_SESSION['alogin']) == 0) {
	header('location:index.php');
} else {

	// $get_books = $dbh->query("SELECT * FROM `booking` WHERE status = 'payment' OR status = 'pending' OR status ='paid'");
	// $result_books = $get_books->fetchAll(PDO::FETCH_ASSOC);

	$get_booked = $dbh->query("SELECT SUM(payment) AS total_payment, COUNT(*) AS total_booked FROM booking WHERE status = 'booked'");
	$result_booked = $get_booked->fetch(PDO::FETCH_ASSOC);


?>
	<!DOCTYPE HTML>
	<html>

	<head>
		<title>TMS | Admin Dashboard</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<script type="application/x-javascript">
			addEventListener("load", function() {
				setTimeout(hideURLbar, 0);
			}, false);

			function hideURLbar() {
				window.scrollTo(0, 1);
			}
		</script>
		<!-- Bootstrap Core CSS -->
		<link href="css/bootstrap.min.css" rel='stylesheet' type='text/css' />
		<!-- Custom CSS -->
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
		<link href="css/style.css" rel='stylesheet' type='text/css' />
		<link rel="stylesheet" href="css/morris.css" type="text/css" />
		<!-- Graph CSS -->
		<link href="css/font-awesome.css" rel="stylesheet">
		<!-- jQuery -->
		<script src="js/jquery-2.1.4.min.js"></script>
		<!-- //jQuery -->
		<link href='//fonts.googleapis.com/css?family=Roboto:700,500,300,100italic,100,400' rel='stylesheet' type='text/css' />
		<link href='//fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
		<!-- lined-icons -->
		<link rel="stylesheet" href="css/icon-font.min.css" type='text/css' />
		<!-- //lined-icons -->
	</head>

	<body>
		<div class="page-container">
			<!--/content-inner-->
			<div class="left-content">
				<?php include('includes/navbar.php'); ?>
				<div class="mother-grid-inner" style="margin-top: 70px;">
					<!--header start here-->

					<!--header end here-->



					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="index.html"></a> <i class="fa fa-angle-right"></i></li>
					</ol>
					<!--four-grids here-->
					<div class="four-grids" style="padding: 0 20px;">



						<a href="manage-users.php" target="_blank">
							<div class="col-md-4 four-grid">
								<div class="four-agileits">
									<div class="icon">
										<i class="glyphicon glyphicon-user" aria-hidden="true"></i>
									</div>
									<div class="four-text">
										<h3>Total Tourist</h3>

										<?php $sql = "SELECT id from tblusers";
										$query = $dbh->prepare($sql);
										$query->execute();
										$results = $query->fetchAll(PDO::FETCH_OBJ);
										$cnt = $query->rowCount();
										?>
										<h4> <?php echo htmlentities($cnt); ?> </h4>


									</div>

								</div>
							</div>
						</a>

						<a href="manage-booking.php" target="_blank">
							<div class="col-md-4 four-grid">
								<div class="four-agileits" style="background: #3f8de0;">
									<div class="icon">
										<i class="glyphicon glyphicon-repeat" aria-hidden="true"></i>
									</div>
									<div class="four-text">
										<h3>Booking Request</h3>

										<h4> <?= $get_books->rowCount(); ?> </h4>
									</div>

								</div>
							</div>
						</a>

						<a href="#">
							<div class="col-md-4 four-grid">
								<div class="four-agileits" style="background: #00780a;">
									<div class="icon">
										<i class="glyphicon glyphicon-calendar" aria-hidden="true"></i>
									</div>
									<div class="four-text">
										<h3>Book Report</h3>
										<h4> <?php echo number_format($result_booked['total_payment']); ?> </h4>
									</div>

								</div>
							</div>
						</a>

						<div class="col-md-12">
							<div class="card mt-4">
								<div class="card-body">
									<canvas id="barChart" style="width:100%;"></canvas>
								</div>
							</div>
						</div>



						<div class="clearfix"></div>
					</div>
					<!--//four-grids here-->


					<div class="inner-block">

					</div>
					<!--inner block end here-->
					<!--copy rights start here-->
					<?php include('includes/footer.php'); ?>
				</div>
			</div>

			<!--/sidebar-menu-->
			<?php include('includes/sidebarmenu.php'); ?>
			<div class="clearfix"></div>
		</div>
		<script>
			var toggle = true;

			$(".sidebar-icon").click(function() {
				if (toggle) {
					$(".page-container").addClass("sidebar-collapsed").removeClass("sidebar-collapsed-back");
					$("#menu span").css({
						"position": "absolute"
					});
				} else {
					$(".page-container").removeClass("sidebar-collapsed").addClass("sidebar-collapsed-back");
					setTimeout(function() {
						$("#menu span").css({
							"position": "relative"
						});
					}, 400);
				}

				toggle = !toggle;
			});
		</script>
		<!--js -->

		<script src="js/jquery.nicescroll.js"></script>
		<script src="js/scripts.js"></script>
		<!-- Bootstrap Core JavaScript -->
		<script src="js/bootstrap.min.js"></script>
		<!-- /Bootstrap Core JavaScript -->
		<!-- morris JavaScript -->
		<script src="js/raphael-min.js"></script>
		<script src="js/morris.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js">
		</script>

		<script>
			$(document).ready(function() {
				//BOX BUTTON SHOW AND CLOSE
				jQuery('.small-graph-box').hover(function() {
					jQuery(this).find('.box-button').fadeIn('fast');
				}, function() {
					jQuery(this).find('.box-button').fadeOut('fast');
				});
				jQuery('.small-graph-box .box-close').click(function() {
					jQuery(this).closest('.small-graph-box').fadeOut(200);
					return false;
				});

				//CHARTS
				function gd(year, day, month) {
					return new Date(year, month - 1, day).getTime();
				}

				graphArea2 = Morris.Area({
					element: 'hero-area',
					padding: 10,
					behaveLikeLine: true,
					gridEnabled: false,
					gridLineColor: '#dddddd',
					axes: true,
					resize: true,
					smooth: true,
					pointSize: 0,
					lineWidth: 0,
					fillOpacity: 0.85,
					data: [{
							period: '2014 Q1',
							iphone: 2668,
							ipad: null,
							itouch: 2649
						},
						{
							period: '2014 Q2',
							iphone: 15780,
							ipad: 13799,
							itouch: 12051
						},
						{
							period: '2014 Q3',
							iphone: 12920,
							ipad: 10975,
							itouch: 9910
						},
						{
							period: '2014 Q4',
							iphone: 8770,
							ipad: 6600,
							itouch: 6695
						},
						{
							period: '2015 Q1',
							iphone: 10820,
							ipad: 10924,
							itouch: 12300
						},
						{
							period: '2015 Q2',
							iphone: 9680,
							ipad: 9010,
							itouch: 7891
						},
						{
							period: '2015 Q3',
							iphone: 4830,
							ipad: 3805,
							itouch: 1598
						},
						{
							period: '2015 Q4',
							iphone: 15083,
							ipad: 8977,
							itouch: 5185
						},
						{
							period: '2016 Q1',
							iphone: 10697,
							ipad: 4470,
							itouch: 2038
						},
						{
							period: '2016 Q2',
							iphone: 8442,
							ipad: 5723,
							itouch: 1801
						}
					],
					lineColors: ['#ff4a43', '#a2d200', '#22beef'],
					xkey: 'period',
					redraw: true,
					ykeys: ['iphone', 'ipad', 'itouch'],
					labels: ['All Visitors', 'Returning Visitors', 'Unique Visitors'],
					pointSize: 2,
					hideHover: 'auto',
					resize: true
				});


			});
		</script>
		<script>
			var xValues = ["Tourist", "Book Request", "Booked"];
			var yValues = [<?php echo htmlentities($cnt); ?>, <?php echo $get_books->rowCount(); ?>, <?= $result_booked['total_booked'] ?>, 1];
			var barColors = ["#fb4c44", "#5386df", "#007b12"];

			new Chart("barChart", {
				type: "bar",
				data: {
					labels: xValues,
					datasets: [{
						backgroundColor: barColors,
						data: yValues
					}]
				},
				options: {
					legend: {
						display: false
					},
					title: {
						display: false
					}
				}
			});
		</script>
	</body>

	</html>
<?php } ?>

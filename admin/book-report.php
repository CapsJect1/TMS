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

    function Sales($month, $conn){
        $year = date('Y');
        // $mth = date('m', strtotime($month));

        $stmt = $conn->query("SELECT SUM(payment) AS TOTAL FROM booking WHERE MONTH(date_created) = '$month' AND YEAR(date_created) = '$year' ");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['TOTAL'];
       
    }
    $january = Sales('01', $dbh);
    $february = Sales('02', $dbh);
    $mar = Sales('03', $dbh);
    $apr = Sales('04', $dbh);
    $may = Sales('05', $dbh);
    $june = Sales('06', $dbh);
    $july = Sales('07', $dbh);
    $aug = Sales('08', $dbh);
    $sept = Sales('09', $dbh);
    $oct = Sales('10', $dbh);
    $nov = Sales('11', $dbh);
    $dec = Sales('12', $dbh);

    $total = $january + $february + $mar + $june + $july + $aug + $sept + $oct + $nov + $dec;


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



						<div class="col-md-12">
							<div class="card mt-4">
								<div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between">
                                    <h3>Booking Report</h3>
                                    <h3>Total: <?= number_format($total, 2) ?></h3>

                                    </div>
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
			var xValues = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
			var yValues = [<?= $january ?>, <?= $february ?>, <?= $mar ?>, <?= $apr ?>, <?= $may ?>, <?= $june ?>, <?= $july ?>,<?= $aug ?>, <?= $sept ?>, <?= $oct ?>, <?= $nov ?>, <?= $dec ?>];
			var barColors = ["#fb4c44", "#5386df", "#007b12", "#fb4c44", "#5386df", "#007b12", "#fb4c44", "#5386df", "#007b12", "#fb4c44", "#5386df", "#007b12", "#fb4c44", "#5386df", "#007b12"];

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

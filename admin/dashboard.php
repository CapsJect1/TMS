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

require 'includes/layout-head.php';
?>

					
						<div class="col-lg-4">
							<a href="manage-users.php" target="_blank" class="text-decoration-none">
								<div class="card bg-danger">
									<div class="card-body text-center">
										<h1 class="text-dark"><i class="fa fa-user"></i></h1>
										<div class="icon">
											<i class="glyphicon glyphicon-user text-light" aria-hidden="true"></i>
										</div>
										<div class="four-text text-light">
											<h3>Total Tourist </h3>

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
						</div>

						<div class="col-lg-4">
							<a href="manage-bookings.php" target="_blank" class="text-decoration-none ">
								<div class="card" style="background: #3f8de0;">
									<div class="card-body text-center">
										<h1 class="text-light"><i class="fa fa-repeat" aria-hidden="true"></i></h1>
										<div class="four-text">
											<h3 class="text-light">Booking Request</h3>

											<h4 class="text-light"> <?= $get_books->rowCount(); ?> </h4>
										</div>

									</div>
								</div>
							</a>
						</div>

						<div class="col-lg-4">
							<a href="book-report.php" class="text-decoration-none">
								<div class="card" style="background: #00780a;">
									<div class="card-body text-center">
										<h1 class="text-light"><i class="fa fa-calendar" aria-hidden="true"></i></h1>
										<div class="four-text">
											<h3 class="text-light">Book Report</h3>
											<h4 class="text-light"> <?php echo number_format($result_booked['total_payment']); ?> </h4>
										</div>
									</div>

								</div>
							</a>
						</div>

						<div class="col-md-12">
							<div class="card mt-4">
								<div class="card-body">
									<canvas id="barChart" style="width:100%;"></canvas>
								</div>
							</div>
						</div>

						<div class="col-12">
							<?php include('includes/footer.php'); ?>
						</div>
					</div>

					
					</div>
					
				</div>
			</div>

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

<?php 
require 'includes/layout-foot.php';

} ?>
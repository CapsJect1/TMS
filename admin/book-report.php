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

	function Sales($month, $conn)
	{
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

	require './includes/layout-head.php';
?>
<!-- Print Button (Updated) -->
<a href="#" class="float-end mt-3 btn btn-primary" id="printButton"><i class="fa fa-print"></i> Print</a>

	<div class="card mt-4 print">
		<div class="card-body">
			<div class="d-flex align-items-center justify-content-between">
				<h3>Booking Report</h3>
				<h3>Total: <?= number_format($total, 2) ?></h3>

			</div>
			<canvas id="barChart" style="width:100%;"></canvas>

			<a href="print-report.php" class="float-end mt-3 btn btn-primary" target="_blank"><i class="fa fa-print"></i> Print</a>
		</div>
	</div>

	<script>
		document.getElementById('printButton').addEventListener('click', function() {
		// If you want to print the current page
		window.print(); 
		
		// Alternatively, you could redirect to the print page and handle printing there
		// window.location.href = 'print-report.php'; // Uncomment if you want to redirect to print page
	});
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

	<!-- <script src="js/jquery.nicescroll.js"></script> -->
	<!-- <script src="js/scripts.js"></script> -->
	<!-- Bootstrap Core JavaScript -->
	<!-- <script src="js/bootstrap.min.js"></script> -->
	<!-- /Bootstrap Core JavaScript -->
	<!-- morris JavaScript -->
	<!-- <script src="js/raphael-min.js"></script> -->
	<!-- <script src="js/morris.js"></script> -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js">
	</script>

	<script>
		var xValues = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
		var yValues = [<?= $january ?>, <?= $february ?>, <?= $mar ?>, <?= $apr ?>, <?= $may ?>, <?= $june ?>, <?= $july ?>, <?= $aug ?>, <?= $sept ?>, <?= $oct ?>, <?= $nov ?>, <?= $dec ?>];
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

<?php
	require 'includes/footer.php';
	require 'includes/layout-foot.php';
} ?>

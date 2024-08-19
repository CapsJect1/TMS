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

	function CountBook($month, $conn){
        $year = date('Y');
        // $mth = date('m', strtotime($month));

        $stmt = $conn->query("SELECT COUNT(payment) AS COUNT FROM booking WHERE MONTH(date_created) = '$month' AND YEAR(date_created) = '$year' ");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['COUNT'];
       
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

	$january_cnt = CountBook('01', $dbh);
    $february_cnt = CountBook('02', $dbh);
    $mar_cnt = CountBook('03', $dbh);
    $apr_cnt = CountBook('04', $dbh);
    $may_cnt = CountBook('05', $dbh);
    $june_cnt = CountBook('06', $dbh);
    $july_cnt = CountBook('07', $dbh);
    $aug_cnt = CountBook('08', $dbh);
    $sept_cnt = CountBook('09', $dbh);
    $oct_cnt = CountBook('10', $dbh);
    $nov_cnt = CountBook('11', $dbh);
    $dec_cnt = CountBook('12', $dbh);

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
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
		<!-- <link href="css/style.css" rel='stylesheet' type='text/css' /> -->
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
        <style>
            	@media print{
                    .dont-print{
                        display: none !important;
                    }

                    @page {size: A4 portrait !important;max-height:100% !important; max-width:100% !important;}
                    
                }

				.btn-primary{
				background: #3f8de0 !important;
			}
        </style>
	</head>

	<body>
		<div class="p-4">
        <div class="card mt-4 print mx-auto w-auto-lg" style="width: 500px !important;">
								<div class="card-body">
                                    <div class="text-center position-relative">
										<h3 class="">Santa Fe Port Tourist</h3>
										<p class="mb-1">Date: <?= date('M d,Y') ?></p>
										<p>Monthly Book Report</p>

										<img src="../images/Santa_Fe_Cebu.png" alt="" width="70" class="position-absolute top-0 start-0">
									
                                    <!-- <h3>Overall Total: <?= number_format($total, 2) ?></h3> -->

                                    </div>
									<!-- <canvas id="barChart"  style="width:100%;"></canvas> -->

									<table class="table table-bordered">
										<thead>
											<th>Month</th>
											<th>Total Booked</th>
											<th>Total</th>
										</thead>
										<tbody>
											<tr>
												<td>January</td>
												<td><?= $january_cnt ?></td>
												<td><?=number_format( $january ?? 0 ) ?></td>
											</tr>
											<tr>
												<td>February</td>
												<td><?= $february_cnt ?></td>
												<td><?=number_format( $february ?? 0 ) ?></td>
											</tr>
											<tr>
												<td>March</td>
												<td><?= $mar_cnt ?></td>
												<td><?=number_format( $mar ?? 0 ) ?></td>
											</tr>
											<tr>
												<td>April</td>
												<td><?= $apr_cnt ?></td>
												<td><?=number_format( $apr ?? 0 ) ?></td>
											</tr>
											<tr>
												<td>May</td>
												<td><?= $may_cnt ?></td>
												<td><?=number_format( $may ?? 0 ) ?></td>
											</tr>
											<tr>
												<td>June</td>
												<td><?= $june_cnt ?></td>
												<td><?=number_format( $june ?? 0 ) ?></td>
											</tr>
											<tr>
												<td>July</td>
												<td><?= $july_cnt ?></td>
												<td><?=number_format( $july ?? 0 ) ?></td>
											</tr>
											<tr>
												<td>August</td>
												<td><?= $aug_cnt ?></td>
												<td><?=number_format( $aug ?? 0 ) ?></td>
											</tr>
											<tr>
												<td>September</td>
												<td><?= $sept_cnt ?></td>
												<td><?=number_format( $sept ?? 0 ) ?></td>
											</tr>
											<tr>
												<td>October</td>
												<td><?= $oct_cnt ?></td>
												<td><?=number_format( $oct ?? 0 ) ?></td>
											</tr>
											<tr>
												<td>November</td>
												<td><?= $nov_cnt ?></td>
												<td><?=number_format( $nov ?? 0 ) ?></td>
											</tr>
											<tr>
												<td>December</td>
												<td><?= $dec_cnt ?></td>
												<td><?=number_format( $dec ?? 0 ) ?></td>
											</tr>
										</tbody>
									</table>

									<div class="text-end">
										<h4>Overall Total: <?= number_format($total, 2) ?></h4>
									</div>


									<a href="javascript:void(0);" onclick="printReceipt()" class="float-end mt-3 dont-print btn btn-primary "><i class="fa fa-print"></i> Print</a>
								</div>
							</div>

		</div>

        
		<!-- <script>
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
		</script> -->
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
			const printReceipt = () => {
				window.print(document.getElementById("receipt"))
			}
		</script>

<!-- <script>
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
		</script> -->

		
	</body>

	</html>
<?php } ?>

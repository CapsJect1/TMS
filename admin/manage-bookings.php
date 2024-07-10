<?php
session_start();
error_reporting(0);
include('includes/config.php');
if (strlen($_SESSION['alogin']) == 0) {
	header('location:index.php');
} else {
	// code for cancel
	if (isset($_REQUEST['decline'])) {
		$bid = intval($_GET['decline']);
		$status = "declined";
		$sql = "UPDATE booking SET status=:status WHERE  id=:bid";
		$query = $dbh->prepare($sql);
		$query->bindParam(':status', $status, PDO::PARAM_STR);
		$query->bindParam(':bid', $bid, PDO::PARAM_STR);
		$query->execute();

		$msg = "Booking Declined successfully";
	}


	if (isset($_REQUEST['accept'])) {
		$bcid = intval($_GET['accept']);
		$status = 'payment';
		$cancelby = 'a';
		$sql = "UPDATE booking SET status=:status WHERE id=:bcid";
		$query = $dbh->prepare($sql);
		$query->bindParam(':status', $status, PDO::PARAM_STR);
		$query->bindParam(':bcid', $bcid, PDO::PARAM_STR);
		$query->execute();
		$msg = "Booking Accepted successfully";
	}

	if (isset($_REQUEST['confirm'])) {
		$bcid = intval($_GET['confirm']);
		$status = 'booked';
		$cancelby = 'a';
		$sql = "UPDATE booking SET status=:status WHERE id=:bcid";
		$query = $dbh->prepare($sql);
		$query->bindParam(':status', $status, PDO::PARAM_STR);
		$query->bindParam(':bcid', $bcid, PDO::PARAM_STR);
		$query->execute();
		$msg = "Booking Confirmed successfully";
	}


?>
	<!DOCTYPE HTML>
	<html>

	<head>
		<title>TMS | Admin manage Bookings</title>
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
		<link href="css/bootstrap.min.css" rel='stylesheet' type='text/css' />
		<link href="css/style.css" rel='stylesheet' type='text/css' />
		<link rel="stylesheet" href="css/morris.css" type="text/css" />
		<link href="css/font-awesome.css" rel="stylesheet">
		<script src="js/jquery-2.1.4.min.js"></script>
		<link rel="stylesheet" type="text/css" href="css/table-style.css" />
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
		<link rel="stylesheet" type="text/css" href="css/basictable.css" />
		<script type="text/javascript" src="js/jquery.basictable.min.js"></script>
		<script type="text/javascript">
			$(document).ready(function() {
				$('#table').basictable();

				$('#table-breakpoint').basictable({
					breakpoint: 768
				});

				$('#table-swap-axis').basictable({
					swapAxis: true
				});

				$('#table-force-off').basictable({
					forceResponsive: false
				});

				$('#table-no-resize').basictable({
					noResize: true
				});

				$('#table-two-axis').basictable();

				$('#table-max-height').basictable({
					tableWrapper: true
				});
			});
		</script>
		<link href='//fonts.googleapis.com/css?family=Roboto:700,500,300,100italic,100,400' rel='stylesheet' type='text/css' />
		<link href='//fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" href="css/icon-font.min.css" type='text/css' />
		<style>
			.errorWrap {
				padding: 10px;
				margin: 0 0 20px 0;
				background: #fff;
				border-left: 4px solid #dd3d36;
				-webkit-box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
				box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
			}

			.succWrap {
				padding: 10px;
				margin: 0 0 20px 0;
				background: #fff;
				border-left: 4px solid #5cb85c;
				-webkit-box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
				box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
			}

			@media print{
				.dont-print{
					display: none !important;
				}
			}
		</style>
	</head>

	<body>
		<div class="page-container">
			<!--/content-inner-->
			<div class="left-content">
				<?php include('includes/navbar.php'); ?>
				<div class="mother-grid-inner" style="margin-top: 70px;">
					<!--header start here-->

					<div class="clearfix dont-print"> </div>
				</div>
				<!--heder end here-->
				<ol class="breadcrumb dont-print">
					<li class="breadcrumb-item"><a href="index.html">Home</a><i class="fa fa-angle-right"></i>Manage
						Bookings</li>
				</ol>
				<div class="agile-grids">
					<!-- tables -->
					<?php if ($error) { ?>
						<div class="errorWrap"><strong>ERROR</strong>:<?php echo htmlentities($error); ?> </div>
					<?php } else if ($msg) { ?>
						<div class="succWrap"><strong>SUCCESS</strong>:<?php echo htmlentities($msg); ?> </div><?php } ?>
					<div class="card">

						<?php if (isset($_GET['show'])) :

							$id = $_GET['show'];
							$stmt = $dbh->query("SELECT b.*, p.PackageName, p.PackagePrice from booking b INNER JOIN tbltourpackages p ON b.package_id = p.PackageId where b.id = '" . $id . "'");
							$row = $stmt->fetch(PDO::FETCH_ASSOC);

							$regular = $row['regular'] * 300;
							$student = $row['student'] * 240;
							$senior_pwd = $row['senior_pwd'] * 214;
							$total_ship = $regular + $student + $senior_pwd;

						?>
							<div class="privacy print" style="margin: 20px 0;">
								<div class="container" style="width: 500px; border: 1px dashed #000; padding: 10px; ">
									<div class="text-center">
										<h4 style="color: black !important; margin-bottom: 0px !important;">Santa Fe Port TSM</h4>
										<p style="margin-bottom: 0px !important; color: #000 !important;">Date: <?= date('Y-m-d') ?></p>
										<p style="margin-bottom: 0px !important; color: #000 !important;">Refer. #: <?= $row['reference_num'] ?></p>
									</div>
									<hr style="color: #000 !important;">

									<p style="color: #000 !important;">Name: <?= ucfirst($row['fname']) . ' ' . ucfirst($row['lname']) ?></p>
									<p style="color: #000 !important;">Email: <?= $row['email'] ?></p>
									<p style="color: #000 !important;">Ship: <?= $row['ship'] ?></p>
									<p style="color: #000 !important;">Package: <?= $row['PackageName'] ?></p>


									<table>
										<thead>
											<th>Regular</th>
											<th>Student</th>
											<th>Senior/PWD</th>
											<th>TOTAL</th>
										</thead>
										<tbody>
											<tr>
												<td><?= $row['regular'] ?></td>
												<td><?= $row['student'] ?></td>
												<td><?= $row['senior_pwd'] ?></td>
												<td><?= $total_ship ?></td>
											</tr>
										</tbody>
									</table>
									<p style="color: #000 !important; margin-bottom: 0px !important; text-align: right;">Ship: <?= number_format($total_ship) ?></p>
									<p style="color: #000 !important; margin: 0px !important; text-align: right;">Package: <?= number_format($row['PackagePrice']) ?></p>
									<h3 style="color: #000 !important; text-align: right; margin: 10px 0 !important;">Total: &#8369 <?= number_format($total_ship + $row['PackagePrice']) ?></h3>
									<h3 style="color: #000 !important; text-align: right; margin: 10px 0 !important;">Cash: &#8369 <?= number_format($row['payment']) ?></h3>
									<?php 
										if ($row['status'] == 'paid') {
											?>
											<img src="data:img/jpeg;base64,<?= base64_encode($row['proof']) ?>" alt="image" style="width: 100%;">
											<?php
										}
									?>
									<div class="dont-print">
										<a href="manage-bookings.php" class="btn btn-default"><i class="fa fa-arrow-left"></i> Back</a>
										<button type="button" class="btn btn-primary" style="color: #fff !important;" onclick="window.print()"><i class="fa fa-print"></i> Print</button>
									</div>
								</div>
							</div>
						<?php else : ?>
							<div class="card-header text-center bg-primary dont-print">
							<h2>Manage Bookings</h2>

						</div>
							<div class="card-body">
								<table class="table table-bordered">
									<thead>
										<tr>
											<th>Booikn id</th>
											<th>Name</th>
											<th>Mobile No.</th>
											<th>Email Id</th>
											<th>Package</th>
											<th>Status </th>
											<th>Action </th>
										</tr>
									</thead>
									<tbody>
										<?php $sql = "SELECT 
										booking.id as bookid,
										tblusers.FullName as fname,
										tblusers.MobileNumber as mnumber,
										tblusers.EmailId as email,
										tbltourpackages.PackageName as pckname,
										booking.package_id as pid,
										booking.status as status
									from  booking
									left join 
										tblusers on booking.user_id=tblusers.id 
									left join 
										tbltourpackages on tbltourpackages.PackageId=booking.package_id
									";
										$query = $dbh->prepare($sql);
										$query->execute();
										$results = $query->fetchAll(PDO::FETCH_OBJ);
										$cnt = 1;
										if ($query->rowCount() > 0) {
											foreach ($results as $result) { ?>
												<tr>
													<td>#BK-<?php echo htmlentities($result->bookid); ?></td>
													<td><?php echo htmlentities($result->fname); ?></td>
													<td><?php echo htmlentities($result->mnumber); ?></td>
													<td><?php echo htmlentities($result->email); ?></td>
													<td><a href="update-package.php?pid=<?php echo htmlentities($result->pid); ?>"><?php echo htmlentities($result->pckname); ?></a>
													</td>

													<td>
														<?= $result->status ?>
													</td>

													<?php if ($result->status == 'payment') {
													?>
														<td>Payment</td>
													<?php } elseif ($result->status == 'booked') {
													?>	
														<td>
														<a href="manage-bookings.php?show=<?php echo htmlentities($result->bookid); ?>" class="text-dark">View</a>
														</td>
													<?php } elseif ($result->status == 'declined') {
													?>	
														<td>Declined</td>
													<?php } else if($result->status == 'pending') { ?>
														<td class="d-flex gap-2"> <a href="manage-bookings.php?decline=<?php echo htmlentities($result->bookid); ?>" onclick="return confirm('Do you really want to decline booking')" class="text-danger">Decline</a>
															/ <a href="manage-bookings.php?accept=<?php echo htmlentities($result->bookid); ?>" onclick="return confirm('Do you really want to accept this request?')">Accept</a> /
															<a href="manage-bookings.php?show=<?php echo htmlentities($result->bookid); ?>" class="text-dark">View</a>
														</td>
													<?php } else if($result->status == 'paid') { ?>
														<td class="d-flex gap-2"> <a href="manage-bookings.php?decline=<?php echo htmlentities($result->bookid); ?>" onclick="return confirm('Do you really want to this decline request?')" class="text-danger">Decline</a>
															/ <a href="manage-bookings.php?confirm=<?php echo htmlentities($result->bookid); ?>" onclick="return confirm('Do you really want to confirm this request?')">Confirm</a> /
															<a href="manage-bookings.php?show=<?php echo htmlentities($result->bookid); ?>" class="text-dark">View</a>
														</td>
													<?php } ?>

												</tr>
										<?php $cnt = $cnt + 1;
											}
										} ?>
									</tbody>
								</table>
							</div>
						<?php endif; ?>
					</div>
					<!-- script-for sticky-nav -->
					<script>
						$(document).ready(function() {
							var navoffeset = $(".header-main").offset().top;
							$(window).scroll(function() {
								var scrollpos = $(window).scrollTop();
								if (scrollpos >= navoffeset) {
									$(".header-main").addClass("fixed");
								} else {
									$(".header-main").removeClass("fixed");
								}
							});

						});
					</script>
					<!-- /script-for sticky-nav -->
					<!--inner block start here-->
					<div class="inner-block dont-print">

					</div>
					<!--inner block end here-->
					<!--copy rights start here-->
					<?php include('includes/footer.php'); ?>
					<!--COPY rights end here-->
				</div>
			</div>
			<!--//content-inner-->
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

	</body>

	</html>
<?php } ?>
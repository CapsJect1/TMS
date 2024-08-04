<?php
session_start();
error_reporting(0);
include('includes/config.php');
if (strlen($_SESSION['login']) == 0) {
	header('location:index.php');
} else {

	if (isset($_GET['submit'])) {
		$id = $_POST['id'];
		$user_id = $_SESSION['user_id'];
		$total = $_POST['total'];
		$status = "paid";
		$cash = $_POST['cash'];
		$image = file_get_contents($_FILES['proof']['tmp_name']);

		if ($cash === $total) {
			$stmt = $dbh->prepare("UPDATE booking SET status=:status, payment=:cash, proof =:image WHERE user_id = :user_id AND id =:id ");
			$stmt->execute([':status' => $status, ':cash' => $cash, ':image' => $image, ':user_id' => $user_id, ':id' => $id]);

			if ($stmt) {
				header("location: issuetickets.php");
			}
		} else {
			header("location: issuetickets.php?pay=$id&error=Cash must be equal to total");
		}
	}

?>
	<!DOCTYPE HTML>
	<html>

	<head>
		<title>TMS | Tourism Management System</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="keywords" content="Tourism Management System In PHP" />
		<script type="applijewelleryion/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
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

			table {
				border: 1px solid #000;
				width: 100%;
			}

			table,
			thead th,
			tr td {
				border: 1px solid #000;
				border-collapse: collapse;
				text-align: center;
			}

			@media print {
				.dont-print {
					display: none !important;
				}
			}
		</style>
	</head>

	<body>
		<!-- top-header -->
		<div class="top-header container-fluid">
			<?php include('includes/header.php'); ?>
			<div class="banner-1 dont-print">
				<div class="container">
					<h1 class="wow zoomIn animated animated" data-wow-delay=".5s" style="visibility: visible; animation-delay: 0.5s; animation-name: zoomIn;">SFP-Management System</h1>
				</div>
			</div>
			<!--- /banner-1 ---->
			<!--- privacy ---->
			<?php
			if (isset($_GET['show'])) {
				$id = $_GET['show'];
				$user_id = $_SESSION['user_id'];
				$stmt = $dbh->query("SELECT b.*, p.PackageName, p.PackagePrice from booking b INNER JOIN tbltourpackages p ON b.package_id = p.PackageId where b.user_id= '" . $user_id . "' AND b.id = '" . $id . "'");
				$row = $stmt->fetch(PDO::FETCH_ASSOC);

				$regular = $row['regular'] * 300;
				$student = $row['student'] * 240;
				$senior_pwd = $row['senior_pwd'] * 214;
				$total_ship = $regular + $student + $senior_pwd;
				
				if ($row['status'] === 'paid') {
					?>
					<script>
						alert("Please for the confirmation");
						window.location.href = "issuetickets.php"
					</script>
					<?php
				}
			?>
				<div class="privacy print">
					<div class="container" style="width: 500px; border: 1px dashed #000; padding: 10px; ">
						<div class="text-center" style="position: relative;">
						<img src="images/Santa_Fe_Cebu.png" alt="logo" style="width: 50px; position: absolute; top: 0px; left: 20px;">
							<h4 style="color: black !important; margin-bottom: 0px !important;">Santa Fe Port TMS</h4>
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
						<h4 style="color: #000 !important; text-align: right; margin: 10px 0 !important;">Cash: &#8369 <?= number_format($row['payment']) ?></h4>

						<h3 style="color: #000 !important; text-align: right; margin: 10px 0 !important;">Total: &#8369 <?= number_format($total_ship + $row['PackagePrice']) ?></h3>

						<div class="dont-print">
							<a href="issuetickets.php" class="btn btn-default"><i class="fa fa-arrow-left"></i> Back</a>

							<?php if ($row['status'] === 'booked') {
					?>
					<button type="button" class="btn btn-primary" style="color: #fff !important;" onclick="window.print()"><i class="fa fa-print"></i> Print</button>
					<?php
				}?>

							
						</div>
					</div>
				</div>
				<?php
			} else if (isset($_GET['pay'])) {
				$id = $_GET['pay'];
				$user_id = $_SESSION['user_id'];
				$status = 'payment';

				$stmt = $dbh->query("SELECT b.*, p.PackageName, p.PackagePrice from booking b INNER JOIN tbltourpackages p ON b.package_id = p.PackageId where b.user_id= '" . $user_id . "' AND b.id = '" . $id . "'");
				$row = $stmt->fetch(PDO::FETCH_ASSOC);

				$regular = $row['regular'] * 300;
				$student = $row['student'] * 240;
				$senior_pwd = $row['senior_pwd'] * 214;
				$total_ship = $regular + $student + $senior_pwd;

				$check = $dbh->prepare("SELECT * FROM booking WHERE user_id = :user_id AND id = :id AND status = :status");
				$check->execute([':user_id' => $user_id, ':id' => $id, ':status' => $status]);
				if ($check->rowCount() > 0) {
				?>
					<form method="post" enctype="multipart/form-data" action="?submit" class="privacy print">
						<input type="hidden" name="id" value="<?= $id ?>">
						<input type="hidden" name="total" value="<?= $total_ship + $row['PackagePrice'] ?>">

						<div class="container" style="width: 500px; border: 1px dashed #000; padding: 10px; ">
						<div class="text-center" style="position: relative;">
						<img src="images/Santa_Fe_Cebu.png" alt="logo" style="width: 50px; position: absolute; top: 0px; left: 20px;">
							<h4 style="color: black !important; margin-bottom: 0px !important;">Santa Fe Port TMS</h4>
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

							<div>
								<img src="./images/gcash.jpg" alt="gcash" style="width: 100%;">
							</div>

							<h4 style="color: #000 !important; margin: 10px 0 !important;">Cash: <input type="text" class="form-control" name="cash" placeholder="Full payment" required></h4>
							<h4 style="color: #000 !important; margin: 10px 0 !important;">Proof of Payment: <input type="file" class="form-control" name="proof" required></h4>
							<p class="text-danger" style="color: red !important;"><?= isset($_GET['error']) ? $_GET['error'] : '' ?></p>
							<div class="dont-print" style="display: flex; justify-content: space-between; margin-top: 20px;">
								<a href="issuetickets.php" class="btn btn-default"><i class="fa fa-arrow-left"></i> Back</a>
								<button type="submit" class="btn btn-primary" style="color: #fff !important;">Submit</button>
							</div>
						</div>
					</form>
				<?php
				} else {
				?>
					<script>
						alert("Invalid booking request")
					</script>
				<?php
				}
			} else {
				?>
				<div class="privacy">
					<div class="container">
						<h3 class="wow fadeInDown animated animated" data-wow-delay=".5s" style="visibility: visible; animation-delay: 0.5s; animation-name: fadeInDown;">Tickets</h3>
						<form name="chngpwd" method="post" onSubmit="return valid();">
							<?php if ($error) { ?><div class="errorWrap"><strong>ERROR</strong>:<?php echo htmlentities($error); ?> </div><?php } else if ($msg) { ?><div class="succWrap"><strong>SUCCESS</strong>:<?php echo htmlentities($msg); ?> </div><?php } ?>
							<p>
							<table class="table table-bordered" border="1" width="100%">
								<tr align="center">
									<th>#</th>
									<th>Ticket Id</th>
									<th>Reference #</th>
									<th>Name</th>
									<th>Package</th>
									<th>Status</th>
									<th>Action</th>
									<!-- <th>Departure Date & Time</th> -->
								</tr>
								<?php

								// $uemail=$_SESSION['login'];;

								$user_id = $_SESSION['user_id'];

								$sql = "SELECT b.*, p.PackageName from booking b INNER JOIN tbltourpackages p ON b.package_id = p.PackageId where b.user_id=:user_id";
								$query = $dbh->prepare($sql);
								$query->bindParam(':user_id', $user_id, PDO::PARAM_STR);
								$query->execute();
								$results = $query->fetchAll(PDO::FETCH_OBJ);
								$cnt = 1;



								if ($query->rowCount() > 0) {
									foreach ($results as $result) {	?>
										<tr align="center">
											<td><?php echo $cnt; ?></td>
											<td width="100">#TKT-<?php echo $result->id; ?></td>
											<td><?php echo $result->reference_num; ?></td>
											<td width="300"><?php echo ucfirst($result->fname) . ' ' . ucfirst($result->lname) ?></td>
											<td><?php
												echo $result->PackageName;
												?></td>
											<td><?php echo $result->status; ?></td>
											<td width="100%" style="display: flex; gap: 10px;">
												<?php if($result->status == 'payment'): ?>
													<a href="issuetickets.php?pay=<?= $result->id ?>" class="btn " style="background: #ddd; color: dark !important;"><i class="fa fa-file"></i> Pay</a>
												<?php endif; ?>
												<!-- <a href="issuetickets.php?show=<?= $result->id ?>" class="btn btn-primary" style="color: white !important;"><i class="fa fa-file"></i> View</a> -->
											</td>
										</tr>
								<?php $cnt = $cnt + 1;
									}
								} ?>
							</table>

							</p>
						</form>


					</div>
				</div>
			<?php
			}
			?>
			<!--- /privacy ---->
			<!--- footer-top ---->
			<!--- /footer-top ---->
			<?php include('includes/footer.php'); ?>
			<!-- signup -->
			<?php include('includes/signup.php'); ?>
			<!-- //signu -->
			<!-- signin -->
			<?php include('includes/signin.php'); ?>
			<!-- //signin -->
			<!-- write us -->
			<?php include('includes/write-us.php'); ?>
	</body>

	</html>
<?php } ?>

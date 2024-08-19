<?php
session_start();
error_reporting(0);
include('includes/config.php');
if (strlen($_SESSION['alogin']) == 0) {
	header('location:index.php');
} else {
	// code for cancel


require 'includes/layout-head.php';
?>
<style>
	@media print{
		.card{
			border: none !important;
		}
		.dont-print{
			display: none !important;
		}
	}
</style>
		<div class="agile-grids">
					<!-- tables -->
					<?php if ($error) { ?>
						<div class="errorWrap"><strong>ERROR</strong>:<?php echo htmlentities($error); ?> </div>
					<?php } else if ($msg) { ?>
						<div class="succWrap"><strong>SUCCESS</strong>:<?php echo htmlentities($msg); ?> </div><?php } ?>
					<div class="card p-4">

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
								<div class="container w-auto" style="width: 500px; border: 1px dashed #000; padding: 10px; ">
								<div class="text-center mt-3" style="position: relative;">
						<img src="../images/Santa_Fe_Cebu.png" alt="logo" style="width: 70px; position: absolute; top: 0px; left: 20px;">
							<h4 style="color: black !important; margin-bottom: 0px !important;">Santa Fe Port TMS</h4>
							<p style="margin-bottom: 0px !important; color: #000 !important;">Date: <?= date('Y-m-d') ?></p>
							<p style="margin-bottom: 0px !important; color: #000 !important;">Refer. #: <?= $row['reference_num'] ?></p>

						</div>
									<hr style="color: #000 !important;">

									<p style="color: #000 !important;">Name: <?= ucfirst($row['fname']) . ' ' . ucfirst($row['lname']) ?></p>
									<p style="color: #000 !important;">Email: <?= $row['email'] ?></p>
									<p style="color: #000 !important;">Ship: <?= $row['ship'] ?></p>
									<p style="color: #000 !important;">Package: <?= $row['PackageName'] ?></p>


									<table class="table table-bordered">
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
									<h5 style="color: #000 !important; text-align: right; margin: 10px 0 !important;">Cash: &#8369 <?= number_format($row['payment']) ?></h5>
									<h3 style="color: #000 !important; text-align: right; margin: 10px 0 !important;">Total: &#8369 <?= number_format($total_ship + $row['PackagePrice']) ?></h3>

									<?php
									if ($row['status'] == 'paid') {
									?>
										<img src="data:img/jpeg;base64,<?= base64_encode($row['proof']) ?>" alt="image" style="width: 100%;">
									<?php
									}
									?>
									<div class="dont-print mt-3">
										<a href="manage-bookings.php" class="btn btn-secondary"><i class="fa fa-arrow-left"></i> Back</a>
										<button type="button" class="btn btn-primary" style="color: #fff !important;" onclick="window.print()"><i class="fa fa-print"></i> Print</button>
									</div>
								</div>
							</div>
						<?php else : ?>
							<div class="card-body">
								<h4>Manage Bookings</h4>

								

								<hr>
								<!-- <form method="post" class="d-flex align-items-center gap-2">
									<input type="search" name="search" class="form-control my-3" placeholder="Search...">
									<button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
								</form> -->
								<div class="table-responsive">
								<table id="table">
									<thead>
										<tr>
											<th>Booking id</th>
											<th>Name</th>
											<th>Mobile No.</th>
											<!-- <th>Email Id</th> -->
											<!-- <th>Package</th> -->
											<th >Date / Time</th>
											<th>Status </th>
											<th>Action </th>
										</tr>
									</thead>
									<tbody>
										<?php
										if (isset($_POST['search'])) {
											$search = $_POST['search'];
											if ($search !== '') {
												$sql = "SELECT 
										booking.id as bookid,
										tblusers.FullName as fname,
										tblusers.MobileNumber as mnumber,
										tblusers.EmailId as email,
										tbltourpackages.PackageName as pckname,
										booking.package_id as pid,
										booking.status as status,
										booking.date_created
									from  booking
									left join 
										tblusers on booking.user_id=tblusers.id 
									left join 
										tbltourpackages on tbltourpackages.PackageId=booking.package_id WHERE tblusers.FullName LIKE '%$search%' OR booking.reference_num LIKE '%$search%'
									";
											} else {
												$sql = "SELECT 
										booking.id as bookid,
										tblusers.FullName as fname,
										tblusers.MobileNumber as mnumber,
										tblusers.EmailId as email,
										tbltourpackages.PackageName as pckname,
										booking.package_id as pid,
										booking.status as status,
										booking.date_created
									from  booking
									left join 
										tblusers on booking.user_id=tblusers.id 
									left join 
										tbltourpackages on tbltourpackages.PackageId=booking.package_id
									";
											}
										} else {
											$sql = "SELECT 
										booking.id as bookid,
										tblusers.FullName as fname,
										tblusers.MobileNumber as mnumber,
										tblusers.EmailId as email,
										tbltourpackages.PackageName as pckname,
										booking.package_id as pid,
										booking.status as status,
										booking.date_created
									from  booking
									left join 
										tblusers on booking.user_id=tblusers.id 
									left join 
										tbltourpackages on tbltourpackages.PackageId=booking.package_id
									";
										}


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
													<!-- <td><?php echo htmlentities($result->email); ?></td> -->
													<!-- <td><a href="update-package.php?pid=<?php echo htmlentities($result->pid); ?>"><?php echo htmlentities($result->pckname); ?></a> -->
													</td>
													<td>
														<?= date('M d,Y : h:i A', strtotime($result->date_created)) ?>
													</td>

													<td>
														<?= $result->status ?>
													</td>

													<?php if ($result->status == 'payment') {
													?>
														<td>Payment</td>
														<?php } elseif ($result->status == 'finished') {
													?>
														<td>
															<a href="manage-bookings.php?show=<?php echo htmlentities($result->bookid); ?>" class="text-dark">View</a>
														
														</td>
													
													<?php } elseif ($result->status == 'booked') {
													?>
														<td class="d-flex gap-1 align-items-center">
															<a href="manage-bookings.php?show=<?php echo htmlentities($result->bookid); ?>" class="btn btn-secondary rounded-1">View</a>
															
															<a href="#" onclick="showMessage('manage-bookings.php?finished=<?php echo htmlentities($result->bookid); ?>', 'Is this book already finished?')
															" class="btn btn-success rounded-1">Finished</a>
														</td>
													<?php } elseif ($result->status == 'declined') {
													?>
														<td class="text-danger">Declined</td>
													<?php } else if ($result->status == 'pending') { ?>
														<td class="d-flex gap-1"> 
															<a href="#" onclick="showMessage('manage-bookings.php?decline=<?php echo htmlentities($result->bookid); ?>', 'Do you really want to decline this request?')
															" class="btn btn-danger rounded-1">Decline</a>
															<a href="#" onclick="showMessage('manage-bookings.php?accept=<?php echo htmlentities($result->bookid); ?>', 'Do you really want to accept this request?')
															" class="btn btn-primary">Accept</a>
															<a href="manage-bookings.php?show=<?php echo htmlentities($result->bookid); ?>" class="btn btn-secondary rounded-1">View</a>
														</td>
													<?php } else if ($result->status == 'paid') { ?>
														<td class="d-flex gap-1"> <a href="#"  onclick="showMessage('manage-bookings.php?decline=<?php echo htmlentities($result->bookid); ?>', 'Do you really want to decline this request?')
															" class="btn btn-danger rounded-1">Decline</a>
															<a href="#" onclick="showMessage('manage-bookings.php?confirm=<?php echo htmlentities($result->bookid); ?>', 'Do you really want to confirm this request?')
															" class="btn btn-primary rounded-1">Confirm</a>
															<a href="manage-bookings.php?show=<?php echo htmlentities($result->bookid); ?>" class="btn btn-secondary rounded-1">View</a>
														</td>
													<?php } ?>

												</tr>
										<?php $cnt = $cnt + 1;
											}
										} ?>
									</tbody>
								</table>
								</div>
							</div>
						<?php endif; ?>
					</div>
				</div>

		<?php
		if (isset($_REQUEST['decline'])) {
			$bid = intval($_GET['decline']);
			$status = "declined";
			$sql = "UPDATE booking SET status=:status WHERE  id=:bid";
			$query = $dbh->prepare($sql);
			$query->bindParam(':status', $status, PDO::PARAM_STR);
			$query->bindParam(':bid', $bid, PDO::PARAM_STR);
			$query->execute();

			?>
					 <script>
						Swal.fire({
							position: 'top-end',
							icon: 'success',
							title: "Booking declined succesfully",
							showConfirmButton: false,
							timer: 1500
						}).then(() => {
							window.location.href = "manage-bookings.php"
						})
					</script>
					<?php 
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
			// $msg = "Booking Accepted successfully";
			?>
					 <script>
						Swal.fire({
							position: 'top-end',
							icon: 'success',
							title: "Booking accepted succesfully",
							showConfirmButton: false,
							timer: 1500
						}).then(() => {
							window.location.href = "manage-bookings.php"
						})
					</script>
					<?php
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
			// $msg = "Booking Confirmed successfully";
			?>
			<script>
			   Swal.fire({
				   position: 'top-end',
				   icon: 'success',
				   title: "Booking confirmed succesfully",
				   showConfirmButton: false,
				   timer: 1500
			   }).then(() => {
				   window.location.href = "manage-bookings.php"
			   })
		   </script>
		   <?php
		}

		if (isset($_REQUEST['finished'])) {
			$bcid = intval($_GET['finished']);
			$status = 'finished';
			$cancelby = 'a';
			$sql = "UPDATE booking SET status=:status WHERE id=:bcid";
			$query = $dbh->prepare($sql);
			$query->bindParam(':status', $status, PDO::PARAM_STR);
			$query->bindParam(':bcid', $bcid, PDO::PARAM_STR);
			$query->execute();
			// $msg = "Booking Confirmed successfully";
			?>
			<script>
			   Swal.fire({
				   position: 'top-end',
				   icon: 'success',
				   title: "Book finished",
				   showConfirmButton: false,
				   timer: 1500
			   }).then(() => {
				   window.location.href = "manage-bookings.php"
			   })
		   </script>
		   <?php
		}
		?>
		<script>
			function showMessage(x, y) {
				Swal.fire({
					title: y,
					showDenyButton: true,
					confirmButtonText: "Yes",
					confirmButtonColor: '#5386df',
					denyButtonText: `No`
				}).then((result) => {
					/* Read more about isConfirmed, isDenied below */
					if (result.isConfirmed) {
						window.location.href = x
					}
				});
			}
		</script>
		<!--js -->
		<!-- <script src="js/jquery.nicescroll.js"></script> -->
		<!-- <script src="js/scripts.js"></script> -->
		<!-- Bootstrap Core JavaScript -->
		<!-- <script src="js/bootstrap.min.js"></script> -->
		<!-- /Bootstrap Core JavaScript -->
<?php 
require 'includes/footer.php';
require 'includes/layout-foot.php';

} ?>
<?php
session_start();
error_reporting(E_ALL);
include('includes/config.php');
if (strlen($_SESSION['alogin']) == 0) {
	header('location:index.php');
} else {
	// code for cancel
	if (isset($_REQUEST['bkid'])) {
		$bid = intval($_GET['bkid']);
		$status = 2;
		$cancelby = 'a';
		$sql = "UPDATE tblbooking SET status=:status,CancelledBy=:cancelby WHERE  BookingId=:bid";
		$query = $dbh->prepare($sql);
		$query->bindParam(':status', $status, PDO::PARAM_STR);
		$query->bindParam(':cancelby', $cancelby, PDO::PARAM_STR);
		$query->bindParam(':bid', $bid, PDO::PARAM_STR);
		$query->execute();

		$msg = "Booking Cancelled successfully";
	}


	if (isset($_REQUEST['bckid'])) {
		$bcid = intval($_GET['bckid']);
		$status = 1;
		$cancelby = 'a';
		$sql = "UPDATE tblbooking SET status=:status WHERE BookingId=:bcid";
		$query = $dbh->prepare($sql);
		$query->bindParam(':status', $status, PDO::PARAM_STR);
		$query->bindParam(':bcid', $bcid, PDO::PARAM_STR);
		$query->execute();
		$msg = "Booking Confirm successfully";
	}
	require 'includes/layout-head.php';

?>

	<div class="card">
		<div class="card-body">
			<div class="agile-tables">

<!-- 				<h2 class="mb-3">Manage <?php echo $_GET['uname']; ?>'s Bookings</h2> -->
<!-- 				<hr> -->
				<div class="table-responsive my-5">
				<table id="table">
					<thead class="bg-danger text-light">
							<th>Booking id</th>
							<th>Name</th>
							<th>Mobile No.</th>
							<th>Package</th>
							<th>Price</th>
							<th>Status </th>
					</thead>
					<tbody>
						<?php 
						$all_book = [];
						$all_payment = [];
						$uid = $_GET['uid'];
						$sql = "SELECT 
						booking.id as bookid,
						tblusers.FullName as fname,
						tblusers.MobileNumber as mnumber,
						tblusers.EmailId as email,
						tbltourpackages.PackageName as pckname,
						booking.package_id as pid,
						booking.status as status,
						booking.payment
						from  booking
						left join 
						tblusers on booking.user_id=tblusers.id 
						left join 
						tbltourpackages on tbltourpackages.PackageId=booking.package_id 
						WHERE
						tblusers.id = '" . $uid . "'";
						$query = $dbh->prepare($sql);
						$query->execute();
						$results = $query->fetchAll(PDO::FETCH_OBJ);
						$cnt = 1;
						if ($query->rowCount() > 0) {
							foreach ($results as $result) {				?>
								<tr>
									<td>#BK-<?php echo htmlentities($result->bookid); ?></td>
									<td><?php echo htmlentities($result->fname); ?></td>
									<td><?php echo htmlentities($result->mnumber); ?></td>
									<td><a href="update-package.php?pid=<?php echo htmlentities($result->pid); ?>"><?php echo htmlentities($result->pckname); ?></a>
									<td><?php echo htmlentities(number_format($result->payment)); ?></td>
									</td>

									<td>
										<?= $result->status ?>
									</td>

									<?php 
										$all_payment[] = $result->payment;
										$all_book[] = 1;
									?>

								</tr>
						<?php $cnt = $cnt + 1;
							}
						} 
						?>
					</tbody>
				</table>

				</div>

				<div class="text-end">
					<h5>Total Booked: <?= array_sum($all_book) ?></h5>
					<h5>Overall Total: <?= number_format(array_sum($all_payment),2) ?></h5>
				</div>
			</div>
		</div>
	</div>

<?php
	require 'includes/footer.php';
	require 'includes/layout-foot.php';
} ?>

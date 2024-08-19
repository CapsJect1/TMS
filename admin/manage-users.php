<?php
session_start();
error_reporting(0);
include('includes/config.php');
if (strlen($_SESSION['alogin']) == 0) {
	header('location:index.php');
} else {
	require 'includes/layout-head.php';
?>

	<div class="card">
		<div class="card-body">
			<div class="d-flex justify-content-between">
			<h4>Manage Tourist</h4>
			<select name="month" class="form-select w-25" id="select-month">
				<option value="" selected disabled>Select Month</option>
				<option value="01">January</option>
				<option value="02">February</option>
				<option value="03">March</option>
				<option value="04">April</option>
				<option value="05">May</option>
				<option value="06">June</option>
				<option value="07">July</option>
				<option value="08">August</option>
				<option value="09">September</option>
				<option value="10">October</option>
				<option value="11">November</option>
				<option value="12">December</option>
			</select>
			</div>
			<hr>
			<!-- <form method="post" class="d-flex align-items-center gap-2">
								<input type="search" name="search" class="form-control my-3" placeholder="Search..." >
								<button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
							</form> -->
			<div class="table-responsive">
				<table class="" id="table">
					<thead>
						<tr>
							<th>#</th>
							<th>Name</th>
							<th>Mobile No.</th>
							<th>Email Id</th>
							<th>RegDate </th>
							<th>Updation Date</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
						// $sql = "SELECT * from tblusers";
						if (isset($_POST['search'])) {
							$search = $_POST['search'];
							if ($search !== '') {
								$sql = "SELECT * from tblusers WHERE FullName LIKE '%$search%'";
							} else {
								$sql = "SELECT * from tblusers";
							}
							$query = $dbh->prepare($sql);
							$query->execute();
						} else  if(isset($_GET['month'])){
							$year = date('Y');
							$month = $_GET['month'];
							$sql = "SELECT * from tblusers WHERE MONTH(RegDate) = :month";
							$query = $dbh->prepare($sql);
							$query->execute([':month' => $month]);
						}
						else {
							$sql = "SELECT * from tblusers";
							$query = $dbh->prepare($sql);
							$query->execute();
						}
						
						
						$results = $query->fetchAll(PDO::FETCH_OBJ);
						$cnt = 1;
						if ($query->rowCount() > 0) {
							foreach ($results as $result) { ?>
								<tr>
									<td><?php echo htmlentities($cnt); ?></td>
									<td><?php echo htmlentities($result->FullName); ?></td>
									<td><?php echo htmlentities($result->MobileNumber); ?></td>
									<td><?php echo htmlentities($result->EmailId); ?></td>
									<td><?php echo htmlentities(date('M d,Y', strtotime($result->RegDate))); ?></td>
									<td><?php echo htmlentities(date('M d,Y', strtotime($result->UpdationDate))); ?></td>
									<td style="width: 130px;"><a href="user-bookings.php?uid=<?php echo htmlentities($result->id); ?>&&uname=<?php echo htmlentities($result->FullName); ?>"
											class="btn btn-primary ">User Bookings</td>
								</tr>
						<?php $cnt = $cnt + 1;
							}
						} ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<script>
		document.getElementById("select-month").onchange = () => {
			let select = document.getElementById("select-month").value;
			window.location.href = "manage-users.php?month=" + select;
		}
	</script>
<?php

	require 'includes/footer.php';
	require 'includes/layout-foot.php';
} ?>
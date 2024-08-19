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
			<h4>Manage Enquiries</h4>
			<hr>
			<div class="table-responsive">
				<table id="table">
					<thead>
						<tr>
							<th>Ticket id</th>
							<th>Name</th>
							<th>Mobile No./ Email</th>

							<th>Subject </th>
							<th>Description </th>
							<th width="250">Posting date </th>
							<th>Action </th>

						</tr>
					</thead>
					<tbody>
						<?php
						if (isset($_POST['search'])) {
							$search = $_POST['search'];
							if ($search !== '') {
								$sql = "SELECT * from tblenquiry WHERE FullName LIKE '%$search%'";
							} else {
								$sql = "SELECT * from tblenquiry";
							}
						} else {
							$sql = "SELECT * from tblenquiry";
						}


						$query = $dbh->prepare($sql);
						$query->execute();
						$results = $query->fetchAll(PDO::FETCH_OBJ);

						if ($query->rowCount() > 0) {
							foreach ($results as $result) { ?>
								<tr>
									<td width="120">#TCKT-<?php echo htmlentities($result->id); ?></td>
									<td width="50"><?php echo htmlentities($result->FullName); ?></td>
									<td width="50"><?php echo htmlentities($result->MobileNumber); ?> /<br />
										<?php echo $result->EmailId; ?></td>


									<td width="200"><?php echo htmlentities($result->Subject); ?></a></td>
									<td width="400"><?php echo htmlentities($result->Description); ?></td>

									<td width="50"><?php echo htmlentities($result->PostingDate); ?></td>
									<?php if ($result->Status == 1) {
									?>
										<td class="d-flex align-items-center gap-1" style="width:130px !important;">Read | <a class="btn btn-danger btn-block"
												href="#"
												onclick="showMessage('manage-enquires.php?action=delete&&id=<?php echo $result->id; ?>', 'Do you really want to delete?')
															">Delete</a></td>
									<?php } else { ?>

										<td class="d-flex gap-1 align-items-center" style="width:130px !important;"><a class="btn btn-primary m-1 btn-block"
												href="#"
												onclick="showMessage('manage-enquires.php?eid=<?php echo htmlentities($result->id); ?>', 'Do you really want to read')
															">Pending</a> |

											<a class="btn btn-danger btn-block"
												href="#"
												onclick="showMessage('manage-enquires.php?action=delete&&id=<?php echo $result->id; ?>', 'Do you really want to delete?')
															">Delete</a>
										</td>
									<?php } ?>
								</tr>
						<?php }
						} ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<?php
	// code for cancel
	if (isset($_REQUEST['eid'])) {
		$eid = intval($_GET['eid']);
		$status = 1;

		$sql = "UPDATE tblenquiry SET Status=:status WHERE  id=:eid";
		$query = $dbh->prepare($sql);
		$query->bindParam(':status', $status, PDO::PARAM_STR);
		$query->bindParam(':eid', $eid, PDO::PARAM_STR);
		$query->execute();

		// $msg = "Enquiry  successfully read";
	?>
		<script>
			Swal.fire({
				position: 'top-end',
				icon: 'success',
				title: "Enquiry succesfully read",
				showConfirmButton: false,
				timer: 1500
			}).then(() => {
				window.location.href = "manage-enquires.php"
			})
		</script>
	<?php
	}


	// Code for deletion
	if ($_GET['action'] == 'delete') {
		$id = intval($_GET['id']);
		//$query=mysqli_query($con,"delete from tbltourpackages where PackageId =:id");
		$sql = "delete from tblenquiry where id =:id";
		$query = $dbh->prepare($sql);
		$query->bindParam(':id', $id, PDO::PARAM_STR);
		$query->execute();
		// echo "<script>alert('Eqnuiry deleted.');</script>";
		// echo "<script>window.location.href='manage-enquires.php'</script>";
	?>
		<script>
			Swal.fire({
				position: 'top-end',
				icon: 'success',
				title: "Enquiry deleted succesfully",
				showConfirmButton: false,
				timer: 1500
			}).then(() => {
				window.location.href = "manage-enquires.php"
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
<?php
	require 'includes/footer.php';
	require 'includes/layout-foot.php';
} ?>
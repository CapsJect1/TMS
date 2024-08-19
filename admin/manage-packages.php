<?php
session_start();
error_reporting(E_ALL);
include('includes/config.php');
if (strlen($_SESSION['alogin']) == 0) {
	header('location:index.php');
} else {
	// Code for deletion

	require 'includes/layout-head.php';
?>
	<div class="card">
		<div class="card-body">

			<h4>Manage Packages</h4>
			<hr>


			<!-- <form method="post" class="d-flex align-items-center gap-2">
				<input type="search" name="search" class="form-control my-3" placeholder="Search...">
				<button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
			</form> -->
			<div class="table-responsive mt-3">
				<table id="table">
					<thead>
						<tr>
							<th>#</th>
							<th>Name</th>
							<th>Type</th>
							<th>Location</th>
							<th>Price</th>
							<th>Creation Date</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
						if (isset($_POST['search'])) {
							$search = $_POST['search'];
							if ($search !== '') {
								$sql = "SELECT * from tblTourPackages WHERE PackageName LIKE '%$search%'";
							} else {
								$sql = "SELECT * from tblTourPackages";
							}
						} else {
							$sql = "SELECT * from tblTourPackages";
						}
						$sql = "SELECT * from tbltourpackages";
						$query = $dbh->prepare($sql);
						$query->execute();
						$results = $query->fetchAll(PDO::FETCH_OBJ);
						$cnt = 1;
						if ($query->rowCount() > 0) {
							foreach ($results as $result) { ?>
								<tr>
									<td><?php echo htmlentities($cnt); ?></td>
									<td><?php echo htmlentities($result->PackageName); ?></td>
									<td><?php echo htmlentities($result->PackageType); ?></td>
									<td><?php echo htmlentities($result->PackageLocation); ?></td>
									<td><?php echo htmlentities($result->PackagePrice); ?></td>
									<td><?php echo htmlentities($result->Creationdate); ?></td>
									<td class="d-flex align-items-center gap-2" style="width: 200px;">
										<a href="update-package.php?pid=<?php echo htmlentities($result->PackageId); ?>" class="btn btn-primary">View
											Details</a>

										<a href="#" onclick="showDelete(<?= $result->PackageId ?>)" class="btn btn-danger btn-block">Delete</a>
									</td>
								</tr>
						<?php $cnt = $cnt + 1;
							}
						} ?>
					</tbody>
				</table>
			</div>
		</div>
		</table>


	</div>

	<?php
	require 'includes/footer.php';

	if (isset($_GET['action'])) {
		if ($_GET['action'] == 'delete') {
			$id = intval($_GET['id']);
			//$query=mysqli_query($con,"delete from tbltourpackages where PackageId =:id");
			$sql = "delete from tbltourpackages where PackageId =:id";
			$query = $dbh->prepare($sql);
			$query->bindParam(':id', $id, PDO::PARAM_STR);
			$query->execute();
			// echo "<script>alert('Package deleted.');</script>";
			// echo "<script>window.location.href='manage-packages.php'</script>";
	?>
			<script>
				Swal.fire({
					position: 'top-end',
					icon: 'success',
					title: "Package deleted succesfully",
					showConfirmButton: false,
					timer: 1500
				}).then(() => {
					window.location.href = "manage-packages.php"
				})
			</script>
	<?php
		}
	}
	?>
	<script>
		function showDelete(x) {
			Swal.fire({
				title: "Do you want to delete this to package?",
				showDenyButton: true,
				confirmButtonText: "Yes",
				confirmButtonColor: '#5386df',
				denyButtonText: `No`
			}).then((result) => {
				/* Read more about isConfirmed, isDenied below */
				if (result.isConfirmed) {
					window.location.href = "manage-packages.php?action=delete&&id=" + x
				}
			});
		}
	</script>
	
<?php
	require 'includes/layout-foot.php';
} ?>
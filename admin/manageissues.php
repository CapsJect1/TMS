<?php
session_start();
error_reporting(0);
include('includes/config.php');
if (strlen($_SESSION['alogin']) == 0) {
	header('location:index.php');
} else {
	// Code for deletion




	require 'includes/layout-head.php';
?>
	<div class="card">
		<div class="card-body">
			<!-- <form method="post" class="d-flex align-items-center gap-2">
				<input type="search" name="search" class="form-control my-3" placeholder="Search...">
				<button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
			</form> -->

			<h4>Manage Issues</h4>
			<hr>
			<div class="table-responsive">
			<table id="table">
				<thead>
					<tr>
						<th>#</th>
						<th>Name</th>
						<th>Mobile No.</th>
						<th>Email Id</th>
						<th>Issues </th>
						<th>Description </th>
						<th>Posting date </th>
						<th>Action </th>

					</tr>
				</thead>
				<tbody>
					<?php
					if (isset($_POST['search'])) {
						$search = $_POST['search'];
						if ($search !== '') {
							$sql = "SELECT tblissues.id as id,tblusers.FullName as fname,tblusers.MobileNumber as mnumber,tblusers.EmailId as email,tblissues.Issue as issue,tblissues.Description as Description,tblissues.PostingDate as PostingDate from tblissues left join tblusers on tblusers.EmailId=tblissues.UserEmail WHERE FullName LIKE '%$search%'";
						} else {
							$sql = "SELECT tblissues.id as id,tblusers.FullName as fname,tblusers.MobileNumber as mnumber,tblusers.EmailId as email,tblissues.Issue as issue,tblissues.Description as Description,tblissues.PostingDate as PostingDate from tblissues left join tblusers on tblusers.EmailId=tblissues.UserEmail";
						}
					} else {
						$sql = "SELECT tblissues.id as id,tblusers.FullName as fname,tblusers.MobileNumber as mnumber,tblusers.EmailId as email,tblissues.Issue as issue,tblissues.Description as Description,tblissues.PostingDate as PostingDate from tblissues left join tblusers on tblusers.EmailId=tblissues.UserEmail";
					}


					$query = $dbh->prepare($sql);
					$query->execute();
					$results = $query->fetchAll(PDO::FETCH_OBJ);

					if ($query->rowCount() > 0) {
						foreach ($results as $result) { ?>
							<tr>
								<td width="120">#00<?php echo htmlentities($result->id); ?></td>
								<td width="50"><?php echo htmlentities($result->fname); ?></td>
								<td width="50"><?php echo htmlentities($result->mnumber); ?></td>
								<td width="50"><?php echo htmlentities($result->email); ?></td>

								<td width="200"><?php echo htmlentities($result->issue); ?></a></td>
								<td width="400"><?php echo htmlentities($result->Description); ?></td>

								<td width="50"><?php echo htmlentities($result->PostingDate); ?></td>


								<td class="d-flex gap-1"><a href="javascript:void(0);"
										onClick="popUpWindow('updateissue.php?iid=<?php echo ($result->id); ?>');"
										class="btn btn-primary btn-block">View </a>

									<a href="#"
										onclick="showMessage('manageissues.php?action=delete&&id=<?php echo $result->id; ?>', 'Do you really want to delete?')
														"
										class="btn btn-danger btn-block">Delete</a>
								</td>

							</tr>
					<?php }
					} ?>
				</tbody>
			</table>
			</div>
		</div>
	</div>

	<?php
	if ($_GET['action'] == 'delete') {
		$id = intval($_GET['id']);
		//$query=mysqli_query($con,"delete from tbltourpackages where PackageId =:id");
		$sql = "delete from tblissues where id =:id";
		$query = $dbh->prepare($sql);
		$query->bindParam(':id', $id, PDO::PARAM_STR);
		$query->execute();
		// echo "<script>alert('Record deleted.');</script>";
		// echo "<script>window.location.href='manageissues.php'</script>";
	?>
		<script>
			Swal.fire({
				position: 'top-end',
				icon: 'success',
				title: "Issue deleted succesfully",
				showConfirmButton: false,
				timer: 1500
			}).then(() => {
				window.location.href = "manageissues.php"
			})
		</script>
	<?php

	}
	?>
	<script language="javascript" type="text/javascript">
			var popUpWin = 0;
			function popUpWindow(URLStr, left, top, width, height) {
				if (popUpWin) {
					if (!popUpWin.closed) popUpWin.close();
				}
				popUpWin = open(URLStr, 'popUpWin', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,copyhistory=yes,width=' + 600 + ',height=' + 600 + ',left=' + left + ', top=' + top + ',screenX=' + left + ',screenY=' + top + '');
			}

		</script>
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